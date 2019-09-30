<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

/**
 */
interface CommandBusInterface
{
    /**
     * @param object $command
     */
    public function dispatch(object $command): void;
}
