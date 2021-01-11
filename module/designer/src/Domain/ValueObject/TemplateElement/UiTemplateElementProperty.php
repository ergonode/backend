<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\Annotation as JMS;

class UiTemplateElementProperty implements TemplateElementPropertyInterface
{
    public const VARIANT = 'ui';

    /**
     * @JMS\Type("string")
     */
    private string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isEqual(TemplateElementPropertyInterface $property): bool
    {
        return
            $property instanceof UiTemplateElementProperty &&
            $this->getVariant() === $property->getVariant() &&
            $this->getLabel() === $property->getLabel();
    }
}
