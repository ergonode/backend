<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DeleteAttributeCommand implements AttributeCommandInterface
{
    private AttributeId $id;

    public function __construct(AttributeId $id)
    {
        $this->id = $id;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }
}
