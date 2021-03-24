<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Mapper;

class ExceptionMapper implements ExceptionMapperInterface
{
    /**
     * @var array
     */
    private array $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritDoc}
     */
    public function map(\Throwable $exception): ?array
    {
        $class = $this->findClass($exception);

        $result = null;
        if (null !== $class) {
            $result = $this->map[$class];
        }

        return $result;
    }

    private function findClass(\Throwable $exception): ?string
    {
        $result = null;

        $class = get_class($exception);
        if (array_key_exists($class, $this->map)) {
            $result = $class;
        } else {
            $intersect = array_intersect(class_parents($exception), array_keys($this->map));
            if (0 !== count($intersect)) {
                $result = key($intersect);
            }
        }

        return $result;
    }
}
