<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

interface TemplateElementInterface
{
    public function getPosition(): Position;

    public function getSize(): Size;

    public function getType(): string;

    public function isEqual(TemplateElementInterface $element): bool;
}
