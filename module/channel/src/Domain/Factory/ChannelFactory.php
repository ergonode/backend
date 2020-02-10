<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Factory;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

/**
 */
class ChannelFactory
{
    /**
     * @param ChannelId          $id
     * @param TranslatableString $name
     * @param SegmentId          $segmentId
     *
     * @return Channel
     *
     * @throws \Exception
     */
    public function create(ChannelId $id, TranslatableString $name, SegmentId $segmentId): Channel
    {
        return new Channel(
            $id,
            $name,
            $segmentId
        );
    }
}
