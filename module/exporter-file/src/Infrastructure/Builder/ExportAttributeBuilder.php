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
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExportAttributeBuilder
{
    /**
     * @var ExportAttributeBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportAttributeBuilderInterface[] $builders
     */
    public function __construct(iterable $builders = [])
    {
        Assert::allIsInstanceOf($builders, ExportAttributeBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(
            array_merge(
                ['_code', '_type', '_language', '_name', '_hint', '_placeholder', '_scope'],
                ...$result
            )
        );
    }


    public function build(AbstractAttribute $attribute, FileExportChannel $channel): ExportData
    {
        $data = new ExportData();

        foreach ($channel->getLanguages() as $language) {
            $line = new ExportLineData();
            $line->set('_code', $attribute->getCode()->getValue());
            $line->set('_type', $attribute->getType());
            $line->set('_language', $language->getCode());
            $line->set('_name', $attribute->getLabel()->get($language));
            $line->set('_hint', $attribute->getHint()->get($language));
            $line->set('_placeholder', $attribute->getPlaceholder()->get($language));
            $line->set('_scope', $attribute->getScope()->getValue());
            foreach ($this->builders as $builder) {
                $builder->build($attribute, $line, $language);
            }
            $data->add($line);
        }

        return $data;
    }
}
