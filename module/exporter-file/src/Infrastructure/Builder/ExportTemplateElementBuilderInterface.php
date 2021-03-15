<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;

interface ExportTemplateElementBuilderInterface
{
    public function header(): array;

    public function build(TemplateElementInterface $element, ExportLineData $data, Language $language): void;
}
