<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Factory;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class ChannelFactory
{
    /**
     * @param ChannelId       $id
     * @param string          $name
     * @param ExportProfileId $exportProfileId
     *
     * @return Channel
     *
     * @throws \Exception
     */
    public function create(ChannelId $id, string $name, ExportProfileId $exportProfileId): Channel
    {
        return new Channel(
            $id,
            $name,
            $exportProfileId
        );
    }
}
