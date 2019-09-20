<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeTemplateElementProperty implements TemplateElementPropertyInterface
{
    public const VARIANT = 'attribute';

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     * @JMS\SerializedName("attribute_id")
     */
    private $attributeId;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $required;

    /**
     * @param AttributeId $attributeId
     * @param bool        $required
     */
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

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }
}
