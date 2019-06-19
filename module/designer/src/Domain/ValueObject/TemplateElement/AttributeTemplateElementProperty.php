<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeTemplateElementProperty extends AbstractTemplateElementProperty
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
     * @JMS\Type("boolean")
     */
    private $required;

    /**
     * @param AttributeId $attributeId
     * @param bool        $required
     */
    public function __construct(AttributeId $attributeId, bool $required)
    {
        $this->attributeId = $attributeId;
        $this->required = $required;
    }

    /**
     * @return string
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
