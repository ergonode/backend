<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject\TemplateElement;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "variant",
 *     map = {
 *         "attribute": "Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty",
 *         "ui": "Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty",
 *     }
 * )
 */
abstract class AbstractTemplateElementProperty
{
    /**
     * @return string
     */
    abstract public function getVariant(): string;
}
