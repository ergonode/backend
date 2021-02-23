<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Webmozart\Assert\Assert;
use Ergonode\Core\Domain\ValueObject\Language;

class ExportProductBuilder
{
    /**
     * @var ExportProductBuilderInterface[]
     */
    private iterable $builders;

    /**
     * @param ExportProductBuilderInterface[]|iterable $builders
     */
    public function __construct(iterable $builders)
    {
        Assert::allIsInstanceOf($builders, ExportProductBuilderInterface::class);

        $this->builders = $builders;
    }

    public function header(): array
    {
        $result = [];
        foreach ($this->builders as $builder) {
            $result[] = $builder->header();
        }

        return array_unique(array_merge(['_sku', '_type', '_language'], ...$result));
    }

    /**
     * @param Language[] $languages
     */
    public function build(AbstractProduct $product, array $languages): ExportData
    {
        $data = new ExportData();

        foreach ($languages as $language) {
            $line = new ExportLineData();
            $line->set('_sku', $product->getSku()->getValue());
            $line->set('_type', $product->getType());
            $line->set('_language', $language->getCode());

            foreach ($this->builders as $builder) {
                $builder->build($product, $line, $language);
            }

            $data->add($line);
        }

        return $data;
    }
}
