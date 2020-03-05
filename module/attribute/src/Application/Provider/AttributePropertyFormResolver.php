<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Provider;

/**
 */
class AttributePropertyFormResolver
{
    /**
     * @var string[]
     */
    private array $items = [];

    /**
     * @param string $type
     * @param string $class
     */
    public function set(string $type, string $class): void
    {
        $this->items[$type] = $class;
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function resolve(string $type): ?string
    {
        if (array_key_exists($type, $this->items)) {
            return $this->items[$type];
        }

        return null;
    }
}
