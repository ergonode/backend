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
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExportOptionBuilder
{
    /**
     * @var ExportOptionBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportOptionBuilderInterface[] $builders
     */
    public function __construct(iterable $builders = [])
    {
        Assert::allIsInstanceOf($builders, ExportOptionBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $line = [];
        foreach ($this->builders as $builder) {
            $line[] = $builder->header();
        }

        return array_unique(array_merge(['_code', '_language', '_label'], ...$line));
    }

    public function build(AbstractOption $option, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();

        foreach ($channel->getLanguages() as $language) {
            $line = new ExportLineData();
            $line->set('_code', $option->getCode()->getValue());
            $line->set('_language', $language->getCode());
            $line->set('_label', $option->getLabel()->get($language));
            foreach ($this->builders as $builder) {
                $builder->build($option, $line, $language);
            }
            $data->add($line);
        }

        return $data;
    }
}
