<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeTemplateReader;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\SerializerInterface;

class ErgonodeTemplateProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'templates.csv';

    private CommandBusInterface $commandBus;
    private SerializerInterface $serializer;

    public function __construct(
        CommandBusInterface $commandBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeTemplateReader($directory, self::FILENAME);

        while ($template = $reader->read()) {
            $command = new ImportTemplateCommand(
                $import->getId(),
                new TemplateId($template->getId()),
                $template->getName(),
                $template->getType(),
                new Position($template->getX(), $template->getY()),
                new Size($template->getWidth(), $template->getHeight()),
                $this->serializer->deserialize(
                    $template->getProperty(),
                    TemplateElementPropertyInterface::class,
                    'json'
                )
            );
            $this->commandBus->dispatch($command);
        }
    }
}
