<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Resolver;

use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;

class TemplateElementTypeResolver
{
    public function resolve(string $type): string
    {
        return ($type === 'SECTION') ? UiTemplateElement::TYPE : AttributeTemplateElement::TYPE;
    }
}
