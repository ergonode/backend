<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateBuilder;

class TemplateProcessor
{
    private ExportTemplateBuilder $templateBuilder;

    public function __construct(ExportTemplateBuilder $templateBuilder)
    {
        $this->templateBuilder = $templateBuilder;
    }

    public function process(FileExportChannel $channel, Template $template): ExportData
    {
        try {
            return $this->templateBuilder->build($template, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for template element %s', $template->getName()),
                $exception
            );
        }
    }
}
