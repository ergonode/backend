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
class UITemplateElement extends AbstractTemplateElement
{
    public const VARIANT = 'ui';

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $label;

    /**
     * @param Position    $position
     * @param Size        $size
     * @param string|null $label
     */
    public function __construct(Position $position, Size $size, string $label = null)
    {
        parent::__construct($position, $size);

        $this->label = $label;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
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
            && $element->getLabel() === $this->label
            && $element->getPosition()->isEqual($this->position)
            && $element->getSize()->isEqual($this->size);
    }
}
