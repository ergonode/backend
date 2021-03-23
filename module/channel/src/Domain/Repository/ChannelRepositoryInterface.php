<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Repository;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface ChannelRepositoryInterface
{
    public function load(ChannelId $id): ?AbstractChannel;

    public function exists(ChannelId $id): bool;

    public function save(AbstractChannel $aggregateRoot): void;

    public function delete(AbstractChannel $aggregateRoot): void;
}
