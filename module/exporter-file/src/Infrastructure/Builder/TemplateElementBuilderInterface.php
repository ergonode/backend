<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;

interface TemplateElementBuilderInterface
{
    public function header(): array;

    public function build(TemplateElementInterface $element, ExportLineData $data): void;
}
