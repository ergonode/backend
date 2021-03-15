<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Query;

use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\Language;
use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Completeness\Domain\ReadModel\CompletenessWidgetModel;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

class CompletenessQuery implements CompletenessQueryInterface
{
    private const TABLE = 'product_completeness';

    private Connection $connection;

    private TranslatorInterface $translator;

    private AttributeRepositoryInterface $repository;

    private LanguageQueryInterface $query;

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

    public function hasCompleteness(ProductId $productId, Language $language): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $count = $qb->select('count(*)')
            ->from(self::TABLE)
            ->where($qb->expr()->eq(sprintf('completeness->>\'%s\'', $language->getCode()), ':percent'))
            ->andWhere($qb->expr()->eq('product_id', ':productId'))
            ->setParameter(':percent', '100')
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($count) {
            return true;
        }

        return false;
    }

    public function getCompletenessCount(Language $language): array
    {
        $result = [];
        $query = $this->connection->createQueryBuilder();
        $all = $count = $query->select('count(*)')
            ->from(self::TABLE)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        foreach ($this->query->getActive() as $active) {
            $code = $active->getCode();
            $translation = $this->translator->trans($code, [], 'language', $language->getCode());

            $query = $this->connection->createQueryBuilder();
            $count = $query->select('count(*)')
                ->from(self::TABLE)
                ->where($query->expr()->eq(sprintf('completeness->>\'%s\'', $code), ':percent'))
                ->setParameter(':percent', '100')
                ->execute()
                ->fetch(\PDO::FETCH_COLUMN);

            $percent = 100;
            if ($all) {
                $percent = round($count / $all * 100, 2);
            }
            $result[] = new CompletenessWidgetModel($code, $translation, $percent);
        }

        return array_values($result);
    }

    public function getAttributeLabel(AttributeId $attributeId, Language $language): string
    {
        $attribute = $this->repository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);
        $label = $attribute->getLabel();

        return $label->has($language) ? $label->get($language) : $attribute->getCode()->getValue();
    }
}
