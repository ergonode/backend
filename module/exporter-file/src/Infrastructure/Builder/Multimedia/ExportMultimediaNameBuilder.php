<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Multimedia;

use Ergonode\ExporterFile\Infrastructure\Builder\ExportMultimediaBuilderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

class ExportMultimediaNameBuilder implements ExportMultimediaBuilderInterface
{
    public function header(): array
    {
        return ['_name'];
    }

    public function build(AbstractMultimedia $multimedia, ExportLineData $line, Language $language): void
    {
        $line->set('_name', $multimedia->getName());
    }
}
