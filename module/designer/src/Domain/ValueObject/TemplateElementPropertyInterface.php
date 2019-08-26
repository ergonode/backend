<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\ValueObject;

/**
 */
interface TemplateElementPropertyInterface
{
    /**
     * @return string
     */
    public function getVariant(): string;
}
