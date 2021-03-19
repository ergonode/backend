<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Option;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;

class CreateOptionCommand implements AttributeCommandInterface
{
    private AggregateId $id;

    private AttributeId $attributeId;

    private OptionKey $code;

    private TranslatableString $label;

    /**
     * @throws \Exception
     */
    public function __construct(AttributeId $attributeId, OptionKey $code, TranslatableString $label)
    {
        $this->id = AggregateId::generate();
        $this->attributeId = $attributeId;
        $this->code = $code;
        $this->label = $label;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function getCode(): OptionKey
    {
        return $this->code;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }
}
