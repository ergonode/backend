<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UiTemplateElementProperty implements TemplateElementPropertyInterface
{
    public const VARIANT = 'ui';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $label;

    /**
     * @param string $label
     */
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

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
