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

class ExportTemplateBuilder
{
    /**
     * @var ExportTemplateBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportTemplateBuilderInterface[] $builders
     */
    public function __construct(iterable $builders = [])
    {
        Assert::allIsInstanceOf($builders, ExportTemplateBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(array_merge(['_name', '_language'], ...$result));
    }

    public function build(Template $template, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();
        foreach ($channel->getLanguages() as $language) {
            $line = new ExportLineData();
            $line->set('_name', $template->getName());
            $line->set('_language', $language->getCode());
            foreach ($this->builders as $builder) {
                $builder->build($template, $line, $language);
            }
            $data->add($line);
        }

        return $data;
    }
}
