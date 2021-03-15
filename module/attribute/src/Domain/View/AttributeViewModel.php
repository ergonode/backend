<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\View;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class AttributeViewModel
{
    private AttributeId $id;

    private AttributeCode $code;

    private string $type;

    /**
     * @var array
     */
    private array $groups;

    /**
     * @param array $groups
     */
    public function __construct(AttributeId $id, AttributeCode $code, string $type, array $groups)
    {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->groups = $groups;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
