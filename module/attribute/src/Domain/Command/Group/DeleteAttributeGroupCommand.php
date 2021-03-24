<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class DeleteAttributeGroupCommand implements AttributeCommandInterface
{
    private AttributeGroupId $id;

    public function __construct(AttributeGroupId $id)
    {
        $this->id = $id;
    }

    public function getId(): AttributeGroupId
    {
        return $this->id;
    }
}
