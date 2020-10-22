<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid\Column\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Infrastructure\Grid\Filter\Option\StatusOption;
use Ergonode\Core\Domain\ValueObject\Color;

class StatusAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var StatusQueryInterface
     */
    private StatusQueryInterface $statusQuery;

    /**
     * @param StatusQueryInterface $statusQuery
     */
    public function __construct(StatusQueryInterface $statusQuery)
    {
        $this->statusQuery = $statusQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof StatusSystemAttribute;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     *
     * @return ColumnInterface
     *
     * @throws \Exception
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $statuses = $this->statusQuery->getAllStatuses($language);

        $options = [];
        foreach ($statuses as $code => $status) {
            $options[] = new StatusOption($code, $code, new Color($status['color']), $status['name']);
        }

        return new LabelColumn(
            StatusSystemAttribute::CODE,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($options)
        );
    }
}
