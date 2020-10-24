<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Resolver;

use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;

class TemplateElementTypeResolver
{
    public function resolve(string $type): string
    {
        return ($type === 'SECTION') ? UiTemplateElementProperty::VARIANT : AttributeTemplateElementProperty::VARIANT;
    }
}
