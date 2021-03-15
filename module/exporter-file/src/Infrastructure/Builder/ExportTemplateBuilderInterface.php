<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Core\Domain\ValueObject\Language;

interface ExportTemplateBuilderInterface
{
    public function header(): array;

    public function build(Template $template, ExportLineData $line, Language $language): void;
}
