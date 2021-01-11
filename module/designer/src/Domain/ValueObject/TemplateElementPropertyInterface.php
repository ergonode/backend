<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\ValueObject;

interface TemplateElementPropertyInterface
{
    public function getVariant(): string;
    public function isEqual(TemplateElementPropertyInterface $property): bool;
}
