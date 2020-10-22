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
use Ergonode\Product\Domain\Query\ProductQueryInterface;

class CompletenessQuery implements CompletenessQueryInterface
{
    private const TABLE = 'product_completeness';

    private Connection $connection;

    private TranslatorInterface $translator;

    private AttributeRepositoryInterface $repository;

    private LanguageQueryInterface $query;

    private ProductQueryInterface $productQuery;

    public function __construct(
        Connection $connection,
        TranslatorInterface $translator,
        AttributeRepositoryInterface $repository,
        LanguageQueryInterface $query,
        ProductQueryInterface $productQuery
    ) {
        $this->connection = $connection;
        $this->translator = $translator;
        $this->repository = $repository;
        $this->query = $query;
        $this->productQuery = $productQuery;
    }

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
        $products = $this->productQuery->getCount();
        $result = [];
        foreach ($this->query->getActive() as $active) {
            $result[$active->getCode()] = new CompletenessWidgetModel(
                $active->getCode(),
                $this->translator->trans($active->getCode(), [], 'language', $language->getCode()),
                0,
            );
        }

        $sqba = $this->connection->createQueryBuilder();
        $sqba->select('product_id, language')
            ->addSelect('(CASE WHEN required <= filled THEN true ELSE false END) AS completed')
            ->from(self::TABLE);

        $sqbb = $this->connection->createQueryBuilder()
            ->select('product_id, language')
            ->addSelect('(CASE WHEN count(product_id) = sum(completed::int) THEN true ELSE false END) AS completed')
            ->from(sprintf('(%s)', $sqba->getSQL()), 't')
            ->groupBy('product_id, language')
            ->having('(CASE WHEN count(product_id) = sum(completed::int) THEN true ELSE false END) = true');

        $records = $this->connection->createQueryBuilder()
            ->select('count(*) as count, language')
            ->from(sprintf('(%s)', $sqbb->getSQL()), 't')
            ->groupBy('language')
            ->execute()
            ->fetchAll();

        foreach ($records as $key => $record) {
            $result[$record['language']] = new CompletenessWidgetModel(
                $record['language'],
                $this->translator->trans($records[$key]['language'], [], 'language', $language->getCode()),
                round(( $record['count'] / $products * 100), 2),
            );
        }

        return array_values($result);
    }

    private function getLabel(AttributeId $attributeId, Language $language): string
    {
        $attribute = $this->repository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);
        $label = $attribute->getLabel();

        return $label->has($language) ? $label->get($language) : $attribute->getCode()->getValue();
    }
}
