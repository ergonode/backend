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
    private $persister;

    /**
     * @var BuilderInterface[]
     */
    private $builders;

    /**
     * @param FilePersister    $persister
     * @param BuilderInterface ...$builders
     */
    public function __construct(FilePersister $persister, BuilderInterface... $builders)
    {
        $this->persister = $persister;
        $this->builders = $builders;
    }

    /**
     * @param string $module
     * @param string $entity
     */
    public function generate(string $module, string $entity): void
    {
        foreach ($this->builders as $builder) {
            $file = $builder->build($module, $entity);
            $this->persister->persist($file, $module);
        }
    }
}
