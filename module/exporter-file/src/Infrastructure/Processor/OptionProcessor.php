<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;

class OptionProcessor
{
    private AttributeRepositoryInterface $attributeRepository;

    private ExportOptionBuilder $optionBuilder;

    public function __construct(AttributeRepositoryInterface $attributeRepository, ExportOptionBuilder $optionBuilder)
    {
        $this->attributeRepository = $attributeRepository;
        $this->optionBuilder = $optionBuilder;
    }

    /**
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractOption $option): ExportData
    {
        try {
            $attribute = $this->attributeRepository->load($option->getAttributeId());
            if (null === $attribute) {
                throw new \InvalidArgumentException('Attribute not found');
            }

            return $this->optionBuilder->build($option, $channel);
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $option->getCode()->getValue()),
                $exception
            );
        }
    }
}
