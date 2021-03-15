<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;

class EditedAtSystemAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof EditedAtSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf('p.created_at AS "%s"', $key);

        $query->addSelect($sql);
    }
}
