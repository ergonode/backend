<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Option;

class FilterOption implements FilterOptionInterface
{
    private string $key;

    private string $code;

    private ?string $label;

    public function __construct(string $key, string $code, ?string $label)
    {
        $this->key = $key;
        $this->code = $code;
        $this->label = $label;
    }

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
