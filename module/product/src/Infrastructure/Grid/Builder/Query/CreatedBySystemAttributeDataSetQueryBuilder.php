<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;

class CreatedBySystemAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CreatedBySystemAttribute;
    }

    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf('(SELECT id, created_by AS "%s" FROM audit)', $key);
        $query->addSelect(sprintf('"%s"', $key));
        $query->leftJoin('p', $sql, sprintf('"%s_JT"', $key), sprintf('"%s_JT".id = p.id', $key));
    }
}
