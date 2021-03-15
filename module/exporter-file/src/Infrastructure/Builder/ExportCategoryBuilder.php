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
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExportCategoryBuilder
{
    /**
     * @var ExportCategoryBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportCategoryBuilderInterface[] $builders
     */
    public function __construct(iterable $builders = [])
    {
        Assert::allIsInstanceOf($builders, ExportCategoryBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $line = [];
        foreach ($this->builders as $builder) {
            $line[] = $builder->header();
        }

        return array_unique(array_merge(['_code', '_name', '_language', ], ...$line));
    }

    public function build(AbstractCategory $category, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();

        foreach ($channel->getLanguages() as $language) {
            $line = new ExportLineData();
            $line->set('_code', $category->getCode()->getValue());
            $line->set('_name', $category->getName()->get($language));
            $line->set('_language', $language->getCode());
            foreach ($this->builders as $builder) {
                $builder->build($category, $line, $language);
            }
            $data->add($line);
        }

        return $data;
    }
}
