<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Workflow\Infrastructure\Factory\Command\CreateWorkflowCommandFactoryInterface;

/**
 */
class CreateWorkflowCommandFactoryProvider
{
    /**
     * @var CreateWorkflowCommandFactoryInterface[]
     */
    private array $factories;

    /**
     * @param CreateWorkflowCommandFactoryInterface ...$factories
     */
    public function __construct(CreateWorkflowCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return CreateWorkflowCommandFactoryInterface
     */
    public function provide(string $type): CreateWorkflowCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find create workflow command factory for type %s', $type)
        );
    }
}
