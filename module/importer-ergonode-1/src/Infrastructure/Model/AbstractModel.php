<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
}