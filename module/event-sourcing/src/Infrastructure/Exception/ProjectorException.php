<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Exception;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
class ProjectorException extends \Exception
{
    public const MESSAGE = 'Can\'t project event %s';

    /**
     * @param DomainEventInterface $event
     * @param \Throwable|null      $previous
     */
    public function __construct(DomainEventInterface $event, \Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, \get_class($event)), 0, $previous);
    }
}
