<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Process;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\Fixture\Exception\FixtureException;
use Ergonode\Fixture\Infrastructure\Loader\FixtureLoader;
use Faker\Generator;
use Nelmio\Alice\Loader\NativeLoader;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;

class FixtureProcess
{
    private FixtureLoader $loader;

    private Generator $generator;

    private CommandBusInterface $commandBus;

    private EventStoreManagerInterface $manager;

    private Connection $connection;

    public function __construct(
        FixtureLoader $loader,
        Generator $generator,
        CommandBusInterface $commandBus,
        EventStoreManagerInterface $manager,
        Connection $connection
    ) {
        $this->loader = $loader;
        $this->generator = $generator;
        $this->commandBus = $commandBus;
        $this->manager = $manager;
        $this->connection = $connection;
    }

    /**
     * @throws FixtureException
     * @throws ConnectionException
     */
    public function process(?string $group = null): void
    {
        try {
            $this->connection->beginTransaction();
            $files = $this->loader->load($group);
            $loader = new NativeLoader($this->generator);

            $objectSet = $loader->loadFiles($files);

            foreach ($objectSet->getObjects() as $object) {
                if ($object instanceof DomainCommandInterface) {
                    $this->commandBus->dispatch($object);
                }
                if ($object instanceof AbstractAggregateRoot) {
                    $this->manager->save($object);
                }
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new FixtureException('Can\'t process fixtures', 0, $exception);
        }
    }
}
