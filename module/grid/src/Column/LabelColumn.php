<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Grid\FilterInterface;

/**
 */
class LabelColumn extends AbstractColumn
{
    public const TYPE = 'LABEL';

    /**
     * @var string
     */
    private $colorField;

    /**
     * @param string               $field
     * @param string               $colorField
     * @param string               $label
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, string $colorField, string $label, ?FilterInterface $filter = null)
    {
        parent::__construct($field, $label, $filter);

        $this->colorField = $colorField;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $id
     * @param array  $row
     *
     * @return array
     */
    public function render(string $id, array $row): array
    {
        return ['label' => $row[$id], 'color' => $row[$this->colorField]];
    }
}
