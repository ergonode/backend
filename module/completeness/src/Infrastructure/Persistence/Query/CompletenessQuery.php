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

/**
 */
class CompletenessQuery implements CompletenessQueryInterface
{
    private const TABLE = 'product_completeness';

    /**
     * @var Connection $connection ;
     */
    private Connection $connection;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param Connection                   $connection
     * @param AttributeRepositoryInterface $repository
     * @param TranslatorInterface          $translator
     */
    public function __construct(
        Connection $connection,
        AttributeRepositoryInterface $repository,
        TranslatorInterface $translator
    ) {
        $this->connection = $connection;
        $this->repository = $repository;
        $this->translator = $translator;
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
        $sqb = $this->connection->createQueryBuilder();
        $sqb->select('product_id, language, sum(required::int) as required, sum(filled::int) as filled')
            ->from(self::TABLE)
            ->where($sqb->expr()->eq('required::int', 1))
            ->groupBy('product_id, language');

        $qb = $this->connection->createQueryBuilder();
        $records = $qb->select('count(*) AS value, language AS code ')
            ->from(sprintf('(%s)', $sqb->getSQL()), 't')
            ->where($qb->expr()->lte('required', 'filled'))
            ->groupBy('language')
            ->execute()->fetchAll();

        $result = [];
        foreach ($records as $key => $record) {
            $result[] = new CompletenessWidgetModel(
                $record['code'],
                $this->translator->trans($records[$key]['code'], [], 'language', $language->getCode()),
                $record['value'],
            );
        }

        return $result;
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
