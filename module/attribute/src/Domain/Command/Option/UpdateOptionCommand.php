<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Option;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;

class UpdateOptionCommand implements AttributeCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private OptionKey $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $label;

    /**
     * @throws \Exception
     */
    public function __construct(AggregateId $id, AttributeId $attributeId, OptionKey $code, TranslatableString $label)
    {
        $this->id = $id;
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
