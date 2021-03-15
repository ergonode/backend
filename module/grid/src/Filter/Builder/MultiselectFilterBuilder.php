<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValue;

class MultiselectFilterBuilder extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool
    {
        return $filter instanceof MultiSelectFilter;
    }

    public function build(QueryBuilder $query, string $field, FilterValue $filter): void
    {
        $operator = $filter->getOperator();
        $givenValue = $filter->getValue();

        if ('=' === $operator) {
            if (null !== $givenValue) {
                $values = explode(',', $givenValue);

                $fields = [];
                foreach ($values as $value) {
                    $fields[] =
                        sprintf(
                            'jsonb_exists_any(to_json("%s")::jsonb, %s::text[])',
                            $field,
                            $query->createNamedParameter(sprintf('{%s}', $value))
                        );
                }
                $query->andWhere(implode(' OR ', $fields));
            } else {
                $query->andWhere(
                    $query->expr()->orX(
                        $query->expr()->eq(sprintf('"%s"::TEXT', $field), '\'[]\''),
                        $query->expr()->isNull(sprintf('"%s"', $field)),
                    )
                );
            }
        }

        if ('!=' === $operator) {
            if (null !== $givenValue) {
                $values = explode(',', $givenValue);

                $fields = [];
                foreach ($values as $value) {
                    $fields[] =
                        sprintf(
                            'NOT jsonb_exists_any(to_json("%s")::jsonb, %s::text[])',
                            $field,
                            $query->createNamedParameter(sprintf('{%s}', $value))
                        );
                }
                $query->andWhere(implode(' AND ', $fields));
            } else {
                $query->andWhere(
                    $query->expr()->andX(
                        $query->expr()->neq(sprintf('"%s"::TEXT', $field), '\'[]\''),
                        $query->expr()->isNotNull(sprintf('"%s"', $field)),
                    )
                );
            }
        }
    }
}
