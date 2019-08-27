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
class CheckColumn extends AbstractColumn
{
    public const TYPE = 'CHECK';
    private const WIDTH = 40;

    /**
     * @param string               $field
     * @param string|null          $label
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, string $label = null, ?FilterInterface $filter = null)
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
