<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity\Element;

use JMS\Serializer\Annotation as JMS;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class UiTemplateElement extends AbstractTemplateElement
{
    public const TYPE = 'ui';

    /**
     * @JMS\Type("string")
     */
    private string $label;

    public function __construct(Position $position, Size $size, string $label)
    {
        parent::__construct($position, $size);

        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return  self::TYPE;
    }

    public function isEqual(TemplateElementInterface $element): bool
    {
        return
            $element instanceof self
            && $this->getPosition()->isEqual($element->getPosition())
            && $this->size->isEqual($element->size)
            && $this->label === $element->getLabel();
    }
}
