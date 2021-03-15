<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportCategoryBuilder;

class CategoryProcessor
{
    private ExportCategoryBuilder $categoryBuilder;

    public function __construct(ExportCategoryBuilder $categoryBuilder)
    {
        $this->categoryBuilder = $categoryBuilder;
    }

    public function process(FileExportChannel $channel, AbstractCategory $category): ExportData
    {
        try {
            return $this->categoryBuilder->build($category, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $category->getCode()->getValue()),
                $exception
            );
        }
    }
}
