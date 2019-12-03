<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Query\FilterQueryInterface;

/**
 */
class AttributeQueryProvider
{
    /**
     * @var FilterQueryInterface ...$strategies
     */
    private $strategies;

    /**
     * @param FilterQueryInterface ...$strategies
     */
    public function __construct(FilterQueryInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param string|null       $value
     *
     * @return QueryBuilder|null
     */
    public function provide(AbstractAttribute $attribute, ?Language $language = null, ?string $value = null): ?QueryBuilder
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($attribute)) {
                return $strategy->query($attribute, $language, $value);
            }
        }

        return null;
    }
}
