<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

abstract class AbstractModel
{
    private array $parameters = [];

    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string|bool $value
     */
    public function addParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * @return string|bool
     */
    public function getParameter(string $name)
    {
        return $this->parameters[$name];
    }
}
