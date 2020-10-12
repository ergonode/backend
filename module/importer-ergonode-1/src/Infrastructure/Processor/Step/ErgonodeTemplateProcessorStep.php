<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor\Step;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeTemplateReader;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\SerializerInterface;

/**
 */
final class ErgonodeTemplateProcessorStep implements ErgonodeProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param CommandBusInterface $commandBus
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CommandBusInterface $commandBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeTemplateReader($directory, 'templates.csv');

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
