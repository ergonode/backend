<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Action;

use Ergonode\Grid\ActionInterface;

abstract class AbstractAction implements ActionInterface
{
    private string $route;

    private ?string $privilege;

    private array $parameters;

    private array $conditions;

    public function __construct(string $route, string $privilege = null, array $parameters = [], array $conditions = [])
    {
        $this->route = $route;
        $this->privilege = $privilege;
        $this->parameters = $parameters;
        $this->conditions = $conditions;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getPrivilege(): ?string
    {
        return $this->privilege;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }
}