<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\TemplateElementBuilder;

class TemplateElementProcessor
{
    private TemplateElementBuilder $builder;

    public function __construct(TemplateElementBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function process(
        FileExportChannel $channel,
        Template $template,
        TemplateElementInterface $element
    ): ExportData {
        try {
            return $this->builder->build($template, $element);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for template element %s', $template->getName()),
                $exception
            );
        }
    }
}
