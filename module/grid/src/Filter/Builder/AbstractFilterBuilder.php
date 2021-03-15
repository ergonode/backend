<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractFilterBuilder
{
    protected function getExpresion(
        QueryBuilder $query,
        string $field,
        string $operator,
        ?string $givenValue = null
    ): string {
        $field = sprintf('"%s"', $field);

        if ('>=' === $operator) {
            return $query->expr()->gte($field, $query->createNamedParameter($givenValue));
        }

        if ('<=' === $operator) {
            return $query->expr()->lte($field, $query->createNamedParameter($givenValue));
        }

        if ('!=' === $operator) {
            if (null !== $givenValue) {
                return \sprintf(
                    '%s::TEXT NOT ILIKE %s',
                    $field,
                    $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($givenValue)))
                );
            }

            return $query->expr()->isNotNull($field);
        }

        if (null !== $givenValue) {
            return \sprintf(
                '%s::TEXT ILIKE %s',
                $field,
                $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($givenValue)))
            );
        }

        return $query->expr()->isNull($field);
    }

    protected function escape(string $value): string
    {
        $replace = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}
