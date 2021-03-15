<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\AttributeCommandResolver;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeAttributeReader;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

class ErgonodeAttributesProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'attributes.csv';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    private AttributeCommandResolver $attributeCommandResolver;

    public function __construct(
        CommandBusInterface $commandBus,
        ImportRepositoryInterface $importRepository,
        AttributeCommandResolver $attributeCommandResolver
    ) {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
        $this->attributeCommandResolver = $attributeCommandResolver;
    }

    public function __invoke(Import $import, ErgonodeZipSource $source, string $directory): void
    {
        if (!$source->import(ErgonodeZipSource::ATTRIBUTES)) {
            return;
        }

        $reader = new ErgonodeAttributeReader($directory, self::FILENAME);

        while ($attribute = $reader->read()) {
            $id = ImportLineId::generate();

            $command = $this->attributeCommandResolver->resolve($id, $import, $attribute);
            $this->importRepository->addLine($id, $import->getId(), 'ATTRIBUTE');
            $this->commandBus->dispatch($command, true);
        }
    }
}
