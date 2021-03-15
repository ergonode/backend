<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\ReadModel;

class CompletenessWidgetModel
{
    private string $code;

    private string $label;

    private float $value;

    public function __construct(string $code, string $label, float $value)
    {
        $this->code = $code;
        $this->label = $label;
        $this->value = $value;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
