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
 * @JMS\Discriminator(
 *     field = "variant",
 *     map = {
 *         "attribute": "Ergonode\Designer\Domain\Entity\AttributeTemplateElement",
 *         "ui": "Ergonode\Designer\Domain\Entity\UITemplateElement",
 *     }
 * )
 */
abstract class AbstractTemplateElement
{
    /**
     * @var Position
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Position")
     */
    protected $position;

    /**
     * @var Size
     *
     * @JMS\Type("Ergonode\Designer\Domain\ValueObject\Size")
     */
    protected $size;

    /**
     * @param Position $position
     * @param Size     $size
     */
    public function __construct(Position $position, Size $size)
    {
        $this->position = $position;
        $this->size = $size;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return string
     */
    abstract public function getVariant(): string;

    /**
     * @param AbstractTemplateElement $element
     *
     * @return bool
     */
    abstract public function isEqual(AbstractTemplateElement $element): bool;
}
