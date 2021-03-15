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

interface AttributeDataSetQueryBuilderInterface
{
    public function supports(AbstractAttribute $attribute): bool;

    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void;
}
