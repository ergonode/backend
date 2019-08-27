<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Resolver;

use Ergonode\Designer\Application\Form\Type\Properties\AttributeElementPropertyType;
use Ergonode\Designer\Application\Form\Type\Properties\SegmentElementPropertyType;

/**
 */
class TemplateElementFormTypeResolver
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function resolve(string $type): string
    {
        return ($type === 'SECTION') ? SegmentElementPropertyType::class : AttributeElementPropertyType::class;
    }
}
