<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface FilterQueryInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function support(AbstractAttribute $attribute): bool;

    /**
     * @param AbstractAttribute $attribute
     * @param Language|null     $language
     * @param string|null       $value
     *
     * @return QueryBuilder
     */
    public function query(AbstractAttribute $attribute, ?Language $language = null, ?string $value = null): QueryBuilder;
}
