<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity\Element;

use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class UiTemplateElement extends AbstractTemplateElement
{
    public const TYPE = 'ui';

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
