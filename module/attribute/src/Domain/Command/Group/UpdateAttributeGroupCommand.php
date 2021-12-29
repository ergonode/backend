<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class UpdateAttributeGroupCommand implements AttributeCommandInterface
{
    private AttributeGroupId $id;

    private TranslatableString $name;

    public function __construct(AttributeGroupId $id, TranslatableString $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
