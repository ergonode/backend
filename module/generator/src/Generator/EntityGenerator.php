<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Generator;

use Ergonode\Generator\Builder\BuilderInterface;
use Ergonode\Generator\Persister\FilePersister;

/**
 */
class EntityGenerator
{
    /**
     * @var FilePersister
     */
    private FilePersister $persister;

    /**
     * @var BuilderInterface[]
     */
    private array $builders;

    /**
     * @param FilePersister    $persister
     * @param BuilderInterface ...$builders
     */
    public function __construct(FilePersister $persister, BuilderInterface...$builders)
    {
        $this->persister = $persister;
        $this->builders = $builders;
    }

    /**
     * @param string $module
     * @param string $entity
     * @param array  $properties
     */
    public function generate(string $module, string $entity, array $properties = []): void
    {
        foreach ($this->builders as $builder) {
            $file = $builder->build($module, $entity, $properties);
            $this->persister->persist($file, $module);
            $namespaces = $file->getNamespaces();
            $namespace = reset($namespaces);
            $classes = $namespace->getClasses();
            $class = reset($classes);
            echo sprintf('Generate class: %s\%s%s', $namespace->getName(), $class->getName(), PHP_EOL);
        }
    }
}
