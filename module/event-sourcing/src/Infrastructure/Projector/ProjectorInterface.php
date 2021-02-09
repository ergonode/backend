<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

interface ProjectorInterface
{
    public function project(AggregateEventInterface $event): void;
}