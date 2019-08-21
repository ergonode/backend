<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer;

use Psr\Container\ContainerInterface;

/**
 */
class HandlerRegistry extends \JMS\Serializer\Handler\HandlerRegistry
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @param ContainerInterface $container
     * @param array              $handlers
     */
    public function __construct(ContainerInterface $container, array $handlers = [])
    {
        parent::__construct($handlers);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler($direction, $typeName, $format)
    {
        $key = sprintf('%s.%s.%s', $typeName, $direction, $format);

        if (array_key_exists($key, $this->map)) {
            $typeName = $this->map[$key];
        }

        $result = null;
        do {
            $handler = parent::getHandler($direction, $typeName, $format);
            if (null !== $handler) {
                $this->map[$key] = $typeName;
                $result = $handler;
                break;
            }
        } while ($typeName = get_parent_class($typeName));

        return $result;
    }
}
