<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

class OptionProcessor
{
    private ExportOptionBuilder $optionBuilder;

    public function __construct(ExportOptionBuilder $optionBuilder)
    {
        $this->optionBuilder = $optionBuilder;
    }

    /**
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractOption $option): ExportData
    {
        try {
            return $this->optionBuilder->build($option, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $option->getCode()->getValue()),
                $exception
            );
        }
    }
}
