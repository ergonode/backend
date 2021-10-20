<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

interface ExportMultimediaBuilderInterface
{
    public function header(): array;

    public function build(AbstractMultimedia $multimedia, ExportLineData $line, Language $language): void;
}
