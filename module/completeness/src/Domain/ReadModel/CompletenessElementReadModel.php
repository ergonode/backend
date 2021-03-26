<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\ReadModel;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class CompletenessElementReadModel
{
    private AttributeId $id;
    private string $name;
    private bool $required;
    private bool $filled;

    public function __construct(AttributeId $id, string $name, bool $required, bool $filled)
    {
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
        $this->filled = $filled;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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
