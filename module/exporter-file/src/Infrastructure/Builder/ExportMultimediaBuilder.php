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
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

class ExportMultimediaBuilder implements ExportHeaderBuilderInterface
{
    /**
     * @var ExportMultimediaBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportMultimediaBuilderInterface[] $builders
     */
    public function __construct(iterable $builders = [])
    {
        Assert::allIsInstanceOf($builders, ExportMultimediaBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(array_merge(['_language', '_alt'], ...$result));
    }

    public function fileName(): string
    {
        return 'multimedia';
    }

    public function build(AbstractMultimedia $multimedia, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();
        foreach ($channel->getLanguages() as $language) {
            $line = new ExportLineData();
            $line->set('_language', $language->getCode());
            $line->set('_alt', $multimedia->getAlt()->get($language));
            foreach ($this->builders as $builder) {
                $builder->build($multimedia, $line, $language);
            }
            $data->add($line);
        }

        return $data;
    }
}
