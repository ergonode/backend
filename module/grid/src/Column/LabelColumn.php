<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Grid\FilterInterface;

/**
 */
class LabelColumn extends AbstractColumn
{
    public const TYPE = 'LABEL';

    /**
     * @param string               $field
     * @param string               $label
     * @param array                $statuses
     * @param FilterInterface|null $filter
     *
     * @throws \Exception
     */
    public function __construct(
        string $field,
        ?string $label = null,
        array $statuses = [],
        FilterInterface $filter = null
    ) {
        parent::__construct($field, $label, $filter);

        $colors = [];
        foreach ($statuses as $code => $status) {
            $colors[$code] = $status['color'];
        }

        $this->setExtension('element_id', AttributeId::fromKey((new AttributeCode($field))->getValue())->getValue());
        $this->setExtension('colors', $colors);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
