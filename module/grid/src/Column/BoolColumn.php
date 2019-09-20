<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Grid\FilterInterface;

/**
 */
class BoolColumn extends AbstractColumn
{
    public const TYPE = 'BOOL';
    private const WIDTH = 100;

    /**
     * @param string               $field
     * @param string               $label
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, string $label, ?FilterInterface $filter = null)
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
     * @return mixed
     */
    public function render(string $id, array $row)
    {
        if ($row[$id]) {
            return true;
        }

        return false;
    }
}
