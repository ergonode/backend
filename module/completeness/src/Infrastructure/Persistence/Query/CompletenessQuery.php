<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Query;

use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\Language;
use Doctrine\DBAL\Connection;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Completeness\Domain\ReadModel\CompletenessWidgetModel;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

/**
 */
class CompletenessQuery implements CompletenessQueryInterface
{
    private const TABLE = 'product_completeness';

    /**
     * @var Connection $connection
     */
    private Connection $connection;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $query;

    /**
     * @param Connection                   $connection
     * @param TranslatorInterface          $translator
     * @param AttributeRepositoryInterface $repository
     * @param LanguageQueryInterface       $query
     */
    public function __construct(
        Connection $connection,
        TranslatorInterface $translator,
        AttributeRepositoryInterface $repository,
        LanguageQueryInterface $query
    ) {
        $this->connection = $connection;
        $this->translator = $translator;
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return CompletenessReadModel
     */
    public function getCompleteness(ProductId $productId, Language $language): CompletenessReadModel
    {
        $qb = $this->connection->createQueryBuilder();
        $records = $qb
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('product_id', ':productId'),
                    $qb->expr()->eq('language', ':language')
                )
            )
            ->setParameter(':productId', $productId->getValue())
            ->setParameter(':language', $language->getCode())
            ->execute()
            ->fetchAll();

        $result = new CompletenessReadModel($language);

        foreach ($records as $record) {
            $attributeId = new AttributeId($record['attribute_id']);
            $element = new CompletenessElementReadModel(
                $attributeId,
                $this->getLabel($attributeId, $language),
                $record['required'],
                $record['filled'],
            );

            $result->addCompletenessElement($element);
        }

        return $result;
    }

    /**
     * @param $language
     *
     * @return CompletenessWidgetModel[]
     */
    public function getCompletenessCount(Language $language): array
    {
        $result = [];
        foreach ($this->query->getActive() as $active) {
            $result[$active->getCode()] = new CompletenessWidgetModel(
                $active->getCode(),
                $this->translator->trans($active->getCode(), [], 'language', $language->getCode()),
                0,
            );
        }

        $sqb = $this->connection->createQueryBuilder();
        $sqb->select('product_id, language, sum(required::int) as required, sum(filled::int) as filled')
            ->from(self::TABLE)
            ->where($sqb->expr()->eq('required::int', 1))
            ->groupBy('product_id, language');

        $qb = $this->connection->createQueryBuilder();
        $records = $qb->select('count(product_id) as product, sum(required) AS required, 
        sum(filled) AS filled, language AS code')
            ->from(sprintf('(%s)', $sqb->getSQL()), 't')
            ->groupBy('language')
            ->execute()->fetchAll();

        foreach ($records as $key => $record) {
            $result[$record['code']] = new CompletenessWidgetModel(
                $record['code'],
                $this->translator->trans($records[$key]['code'], [], 'language', $language->getCode()),
                round(
                    ($record['product'] - ($record['required'] - $record['filled'])) / $record['product'] * 100,
                    2
                ),
            );
        }

        return array_values($result);
    }

    /**
     * @param AttributeId $attributeId
     * @param Language    $language
     *
     * @return string
     */
    private function getLabel(AttributeId $attributeId, Language $language): string
    {
        $attribute = $this->repository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);
        $label = $attribute->getLabel();

        return $label->has($language) ? $label->get($language) : $attribute->getCode()->getValue();
    }
}
