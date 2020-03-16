<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

/**
 */
class DataSetQueryBuilder
{
    /**
     * @var AttributeDataSetQueryBuilderInterface[]
     */
    private array $strategies;

    /**
     * @param AttributeDataSetQueryBuilderInterface ...$strategies
     */
    public function __construct(AttributeDataSetQueryBuilderInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param QueryBuilder      $query
     * @param string            $key
     * @param AbstractAttribute $attribute
     * @param Language          $language
     */
    public function provide(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($attribute)) {
                $strategy->addSelect($query, $key, $attribute, $language);

                return;
            }
        }

        $query->addSelect(sprintf(
            '(SELECT value FROM value_translation vt JOIN product_value pv ON  pv.value_id = vt.value_id  WHERE 
            pv.attribute_id = \'%s\' AND (vt.language = \'%s\' OR vt.language IS NULL) AND pv.product_id = p.id LIMIT 1)
              AS "%s"',
            $attribute->getId()->getValue(),
            $language->getCode(),
            $key
        ));
    }
}
