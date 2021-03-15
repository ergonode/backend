<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilder;

class ProductProcessor
{
    private ExportProductBuilder $productBuilder;

    public function __construct(ExportProductBuilder $productBuilder)
    {
        $this->productBuilder = $productBuilder;
    }

    public function process(FileExportChannel $channel, AbstractProduct $product): ExportData
    {
        try {
            return $this->productBuilder->build($product, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $product->getSku()->getValue()),
                $exception
            );
        }
    }
}
