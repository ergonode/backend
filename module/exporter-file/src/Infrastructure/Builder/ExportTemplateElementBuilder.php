<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExportTemplateElementBuilder
{
    /**
     * @var ExportTemplateElementBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportTemplateElementBuilderInterface[] $builders
     */
    public function __construct(iterable $builders)
    {
        Assert::allIsInstanceOf($builders, ExportTemplateElementBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(array_merge(['_name', '_type', '_language', '_x', '_y', '_width', '_height'], ...$result));
    }

    public function build(Template $template, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();

        foreach ($channel->getLanguages() as $language) {
            foreach ($template->getElements() as $element) {
                $line = new ExportLineData();
                $line->set('_name', $template->getName());
                $line->set('_type', $element->getType());
                $line->set('_language', $language->getCode());
                $line->set('_x', (string) $element->getPosition()->getX());
                $line->set('_y', (string) $element->getPosition()->getY());
                $line->set('_width', (string) $element->getSize()->getWidth());
                $line->set('_height', (string) $element->getSize()->getHeight());
                foreach ($this->builders as $builder) {
                    $builder->build($element, $line, $language);
                }
                $data->add($line);
            }
        }

        return $data;
    }
}
