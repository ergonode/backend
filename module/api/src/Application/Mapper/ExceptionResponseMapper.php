<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Mapper;

/**
 */
class ExceptionResponseMapper
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * @param \Exception $exception
     *
     * @return array|null
     */
    public function map(\Exception $exception): ?array
    {
        $class = $this->findClass($exception);

        if (null === $class) {
            return null;
        }

        return $this->map[$class];
    }

    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    private function findClass(\Exception $exception): ?string
    {
        $class = get_class($exception);
        if (array_key_exists($class, $this->map)) {
            return $class;
        }

        $intersect = array_intersect(class_parents($exception), array_keys($this->map));
        if (0 === count($intersect)) {
            return null;
        }

        return key($intersect);
    }
}
