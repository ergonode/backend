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
class DateColumn extends AbstractColumn
{
    public const TYPE = 'DATE';
    private const WIDTH = 220;

    /**
     * @param string               $field
     * @param string               $label
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, ?string $label = null, ?FilterInterface $filter = null)
    {
        parent::__construct($field, $label, $filter);

        $this->setWidth(self::WIDTH);
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
     * @return string|null
     */
    public function render(string $id, array $row): ?string
    {
        return $row[$id];
    }
}
