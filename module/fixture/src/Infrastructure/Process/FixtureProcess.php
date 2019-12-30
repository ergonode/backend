<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Process;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Fixture\Exception\FixtureException;
use Ergonode\Fixture\Infrastructure\Loader\FixtureLoader;
use Ergonode\Fixture\Infrastructure\Manager\FixtureManager;
use Faker\Generator;
use Nelmio\Alice\Loader\NativeLoader;

/**
 */
class FixtureProcess
{
    /**
     * @var FixtureLoader
     */
    private $loader;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var FixtureManager
     */
    private $manager;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * FixtureProcess constructor.
     *
     * @param FixtureLoader  $loader
     * @param Generator      $generator
     * @param FixtureManager $manager
     * @param Connection     $connection
     */
    public function __construct(
        FixtureLoader $loader,
        Generator $generator,
        FixtureManager $manager,
        Connection $connection
    ) {
        $this->loader = $loader;
        $this->generator = $generator;
        $this->manager = $manager;
        $this->connection = $connection;
    }

    /**
     * @param string|null $group
     *
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

            foreach ($objectSet->getObjects() as $key => $object) {
                if ($object instanceof AbstractAggregateRoot) {
                    $this->manager->persist($object);
                }
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new FixtureException('Cant process fixtures', 0, $exception);
        }
    }
}
