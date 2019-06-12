<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class ActionColumn extends AbstractColumn
{
    public const TYPE = 'ACTION';
    private const WIDTH = 30;

    /**
     * @param string $field
     * @param string $label
     */
    public function __construct(string $field, ?string $label = null)
    {
        parent::__construct($field, $label);

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
        return true;
    }
}
