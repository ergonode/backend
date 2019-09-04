<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Provider;

/**
 */
interface DomainEventProviderInterface
{
    /**
     * @param string $eventClass
     *
     * @return string
     */
    public function provideEventId(string $eventClass): string;
}
