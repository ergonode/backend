<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer;

use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use Psr\Container\ContainerInterface;

/**
 */
class HandlerRegistry implements HandlerRegistryInterface
{
    private const MAP_KEY_TEMPLATE = '%s.%s.%s';

    /**
     * @var array
     */
    private array $map = [];

    /**
     * @var HandlerRegistryInterface
     */
    private HandlerRegistryInterface $registry;

    /**
     * @param ContainerInterface            $container
     * @param iterable                      $handlers
     * @param HandlerRegistryInterface|null $registry
     */
    public function __construct(
        ContainerInterface $container,
        iterable $handlers,
        HandlerRegistryInterface $registry = null
    ) {
        foreach ($handlers as $handler) {
            $registry->registerSubscribingHandler($handler);
        }

        $this->registry = $registry;
    }

    /**
     * {@inheritDoc}
     */
    public function registerHandler(int $direction, string $typeName, string $format, $handler): void
    {
        $this->registry->registerHandler($direction, $typeName, $format, $handler);
    }

    /**
     * {@inheritDoc}
     */
    public function registerSubscribingHandler(SubscribingHandlerInterface $handler): void
    {
        $this->registry->registerSubscribingHandler($handler);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandler($direction, $typeName, $format)
    {
        $key = sprintf(self::MAP_KEY_TEMPLATE, $typeName, $direction, $format);

        if (array_key_exists($key, $this->map)) {
            $typeName = $this->map[$key];
        }

        $handler = null;
        do {
            $handler = $this->registry->getHandler($direction, $typeName, $format);
            if (null !== $handler) {
                $this->map[$key] = $typeName;
                break;
            }
        } while ($typeName = get_parent_class($typeName));

        return $handler;
    }
}
