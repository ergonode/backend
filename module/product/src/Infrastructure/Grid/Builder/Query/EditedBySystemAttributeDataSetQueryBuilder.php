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
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;

class EditedBySystemAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof EditedBySystemAttribute;
    }

    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf(
            '(
            SELECT 
                a.id, 
                COALESCE(u.first_name || \' \' || u.last_name, \'System\') AS "%s" 
            FROM audit a 
            LEFT JOIN users u ON u.id = a.edited_by)',
            $key
        );
        $query->addSelect(sprintf('"%s"', $key));
        $query->leftJoin('p', $sql, sprintf('"%s_JT"', $key), sprintf('"%s_JT".id = p.id', $key));
    }
}
