<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeTemplateElement extends AbstractTemplateElement
{
    public const VARIANT = 'attribute';

    /**
     * @var TemplateElementId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateElementId")
     */
    private $elementId;

    /**
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private $required;

    /**
     * @param Position          $position
     * @param Size              $size
     * @param TemplateElementId $elementId
     * @param bool              $required
     */
    public function __construct(Position $position, Size $size, TemplateElementId $elementId, bool $required = false)
    {
        parent::__construct($position, $size);

        $this->elementId = $elementId;
        $this->required = $required;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return TemplateElementId
     */
    public function getElementId(): TemplateElementId
    {
        return $this->elementId;
    }

    /**
     * @return string
     */
    public function getVariant(): string
    {
        return self::VARIANT;
    }


    /**
     * @param AbstractTemplateElement $element
     *
     * @return bool
     */
    public function isEqual(AbstractTemplateElement $element): bool
    {
        return $element instanceof self
            && $element->isRequired() === $this->required
            && $element->getPosition()->isEqual($this->position)
            && $element->getSize()->isEqual($this->size)
            && $element->getElementId()->equal($this->elementId);
    }
}
