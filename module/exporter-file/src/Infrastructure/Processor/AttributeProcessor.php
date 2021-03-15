<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilder;

class AttributeProcessor
{
    private ExportAttributeBuilder $attributeBuilder;

    public function __construct(ExportAttributeBuilder $attributeBuilder)
    {
        $this->attributeBuilder = $attributeBuilder;
    }

    public function process(FileExportChannel $channel, AbstractAttribute $attribute): ExportData
    {
        try {
            return $this->attributeBuilder->build($attribute, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $attribute->getCode()->getValue()),
                $exception
            );
        }
    }
}
