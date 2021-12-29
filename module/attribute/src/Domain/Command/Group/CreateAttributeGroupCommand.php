<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class CreateAttributeGroupCommand implements AttributeCommandInterface
{
    private AttributeGroupId $id;

    private AttributeGroupCode $code;

    private TranslatableString $name;

    /**
     * @throws \Exception
     */
    public function __construct(AttributeGroupCode $code, TranslatableString $name)
    {
        $this->id = AttributeGroupId::generate();
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    public function getCode(): AttributeGroupCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
