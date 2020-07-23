<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Provider;

/**
 */
class ChannelTypeProvider
{
    /**
     * @var string[]
     */
    private array $types;

    /**
     * @param string ...$classes
     *
     * @throws \ReflectionException
     */
    public function __construct(string ...$classes)
    {
        $this->types = [];
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
