<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity\Element;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class AttributeTemplateElement extends AbstractTemplateElement
{
    public const TYPE = 'attribute';

    private AttributeId $attributeId;

    private bool $required;

    public function __construct(Position $position, Size $size, AttributeId $attributeId, bool $required = true)
    {
        parent::__construct($position, $size);

        $this->attributeId = $attributeId;
        $this->required = $required;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function isEqual(TemplateElementInterface $element): bool
    {
        return
            $element instanceof self
            && $this->getPosition()->isEqual($element->getPosition())
            && $this->getSize()->isEqual($element->getSize())
            && $this->isRequired() === $element->isRequired()
            && $this->getAttributeId()->isEqual($element->getAttributeId());
    }
}
