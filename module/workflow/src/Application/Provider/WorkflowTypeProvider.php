<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Provider;

class WorkflowTypeProvider
{
    /**
     * @var string[]
     */
    private array $types;

    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
         return array_keys($this->types);
    }
}
