<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class CompletenessCalculatorLine
{
    private AttributeId $id;
    private bool $required;
    private bool $filled;

    public function __construct(AttributeId $id, bool $required, bool $filled)
    {
        $this->id = $id;
        $this->required = $required;
        $this->filled = $filled;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->id;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isFilled(): bool
    {
        return $this->filled;
    }
}
