<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Provider;

class ProductTypeProvider
{
    /**
     * @var string[]
     */
    private array $types;

    /**
     * @throws \ReflectionException
     */
    public function __construct(string ...$classes)
    {
        foreach ($classes as $class) {
            $type = (new \ReflectionClass($class))->getConstant('TYPE');
            $this->types[$type] = $class;
        }
    }

    /**
     * @return array
     */
    public function provide(): array
    {
         return array_keys($this->types);
    }
}
