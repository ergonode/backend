<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Strategy;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

interface TemplateElementMapInterface
{
    public function support(TemplateElementInterface $element): bool;

    public function map(TemplateElementInterface $element): array;
}
