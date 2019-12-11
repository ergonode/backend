<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface AttributeDataSetQueryBuilderInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function support(AbstractAttribute $attribute): bool;

    /**
     * @param QueryBuilder      $query
     * @param string            $key
     * @param AbstractAttribute $attribute
     * @param Language          $language
     *
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void;
}
