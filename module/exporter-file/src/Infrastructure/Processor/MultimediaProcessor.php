<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportMultimediaBuilder;

class MultimediaProcessor
{
    private ExportMultimediaBuilder $multimediaBuilder;

    public function __construct(ExportMultimediaBuilder $multimediaBuilder)
    {
        $this->multimediaBuilder = $multimediaBuilder;
    }

    public function process(FileExportChannel $channel, AbstractMultimedia $multimedia): ExportData
    {
        try {
            return $this->multimediaBuilder->build($multimedia, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s multimedia', $multimedia->getName()),
                $exception
            );
        }
    }
}
