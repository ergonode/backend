<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid\Filter\Option;

use Ergonode\Grid\Filter\Option\FilterOptionInterface;
use Ergonode\Core\Domain\ValueObject\Color;

class StatusOption implements FilterOptionInterface
{
    private string $key;

    private string $code;

    private Color $color;

    private ?string $label;

    public function __construct(string $key, string $code, Color $color, ?string $label)
    {
        $this->key = $key;
        $this->code = $code;
        $this->color = $color;
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
            'color' => $this->color->getValue(),
        ];

        if ($this->label) {
            $result['label'] = $this->label;
        }

        return $result;
    }
}
