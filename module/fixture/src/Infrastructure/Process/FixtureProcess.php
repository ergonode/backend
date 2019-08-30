<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Process;

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
     * @param FixtureLoader  $loader
     * @param Generator      $generator
     * @param FixtureManager $manager
     */
    public function __construct(FixtureLoader $loader, Generator $generator, FixtureManager $manager)
    {
        $this->loader = $loader;
        $this->generator = $generator;
        $this->manager = $manager;
    }

    /**
     * @param string|null $group
     *
     * @throws FixtureException
     */
    public function process(?string $group = null): void
    {
        try {
            $files = $this->loader->load($group);
            $loader = new NativeLoader($this->generator);

            $objectSet = $loader->loadFiles($files);

            foreach ($objectSet->getObjects() as $key => $object) {
                if ($object instanceof AbstractAggregateRoot) {
                    $this->manager->persist($object);
                }
            }
        } catch (\Throwable $exception) {
            throw new FixtureException('Cant process fixtures', 0, $exception);
        }
    }
}
