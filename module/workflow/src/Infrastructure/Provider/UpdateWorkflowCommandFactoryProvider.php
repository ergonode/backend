<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\Workflow\Infrastructure\Factory\Command\UpdateWorkflowCommandFactoryInterface;

class UpdateWorkflowCommandFactoryProvider
{
    /**
     * @var UpdateWorkflowCommandFactoryInterface[]
     */
    private array $factories;

    /**
     * @param UpdateWorkflowCommandFactoryInterface ...$factories
     */
    public function __construct(UpdateWorkflowCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return UpdateWorkflowCommandFactoryInterface
     */
    public function provide(string $type): UpdateWorkflowCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find update workflow command factory for type %s', $type)
        );
    }
}
