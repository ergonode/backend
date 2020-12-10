<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class AttributeTemplateElementProperty implements TemplateElementPropertyInterface
{
    public const VARIANT = 'attribute';

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     * @JMS\SerializedName("attribute_id")
     */
    private AttributeId $attributeId;

    /**
     * @JMS\Type("bool")
     */
    private bool $required;

    public function __construct(AttributeId $attributeId, bool $required = false)
    {
        $this->attributeId = $attributeId;
        $this->required = $required;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getVariant(): string
    {
        return self::VARIANT;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isEqual(TemplateElementPropertyInterface $property): bool
    {
        return
            $property instanceof AttributeTemplateElementProperty &&
            $this->getVariant() === $property->getVariant() &&
            $this->isRequired() === $property->isRequired() &&
            $this->getAttributeId()->isEqual($property->getAttributeId());
    }
}
