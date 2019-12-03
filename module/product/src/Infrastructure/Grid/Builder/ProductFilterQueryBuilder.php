<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\AttributeQueryProvider;
use Webmozart\Assert\Assert;

/**
 */
class ProductFilterQueryBuilder
{
    /**
     * @var AttributeQueryProvider
     */
    private $provider;

    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeQueryProvider       $provider
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeQueryProvider $provider, AttributeRepositoryInterface $repository)
    {
        $this->provider = $provider;
        $this->repository = $repository;
    }

    /**
     * @param QueryBuilder          $queryBuilder
     * @param FilterValueCollection $values
     * @param array                 $columns
     *
     * @return QueryBuilder
     * @throws \Exception
     */
    public function getFilter(QueryBuilder $queryBuilder, FilterValueCollection $values, array $columns = []): QueryBuilder
    {
        Assert::allIsInstanceOf($columns, ColumnInterface::class);
        $value = null;
        foreach ($columns as $key => $column) {
            $filter = $column->getFilter();
            if ($filter instanceof FilterInterface) {
                $attributeId = AttributeId::fromKey(new AttributeCode($column->getField()));
                $attribute = $this->repository->load($attributeId);
                if ($attribute) {
                    /** @var QueryBuilder $query */
                    $query = $this->provider->provide($attribute, null, $value);

                    if ($query) {
                        if ($value) {
                            $queryBuilder->join('t', sprintf('(%s)', $query->getSQL()), 'dd', 'dd.id = t.id');
                        } else {
                            $queryBuilder->where($queryBuilder->expr()->notIn('id', $query->getSQL()));
                        }

                        foreach ($query->getParameters() as $parameter => $value) {
                            $queryBuilder->setParameter($parameter, $value);
                        }
                    }
                }
            }
        }

        return $queryBuilder;
    }
}
