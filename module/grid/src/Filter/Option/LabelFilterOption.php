<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter\Option;

/**
 */
class LabelFilterOption implements FilterOptionInterface
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $label;

    /**
     * @param string $key
     * @param string $label
     */
    public function __construct(string $key, string $label)
    {
        $this->key = $key;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            'label' => $this->label,
        ];
    }
}
