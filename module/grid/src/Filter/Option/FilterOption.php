<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter\Option;

class FilterOption implements FilterOptionInterface
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var string|null
     */
    private ?string $label;

    /**
     * @param string      $key
     * @param string      $code
     * @param string|null $label
     */
    public function __construct(string $key, string $code, ?string $label)
    {
        $this->key = $key;
        $this->code = $code;
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
        $result = [
            'code' => $this->code,
        ];

        if ($this->label) {
            $result['label'] = $this->label;
        }

        return $result;
    }
}
