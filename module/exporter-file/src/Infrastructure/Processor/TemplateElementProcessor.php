<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilder;

class TemplateElementProcessor
{
    private ExportTemplateElementBuilder $builder;

    public function __construct(ExportTemplateElementBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function process(
        FileExportChannel $channel,
        Template $template
    ): ExportData {
        try {
            return $this->builder->build($template, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for template element %s', $template->getName()),
                $exception
            );
        }
    }
}
