<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
interface Shopware6MultimediaRepositoryInterface
{
    /**
     * @param ChannelId    $channelId
     * @param MultimediaId $multimediaId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, MultimediaId $multimediaId): ?string;

    /**
     * @param ChannelId    $channelId
     * @param MultimediaId $multimediaId
     * @param string       $shopwareId
     */
    public function save(ChannelId $channelId, MultimediaId $multimediaId, string $shopwareId): void;

    /**
     * @param ChannelId    $channelId
     * @param MultimediaId $multimediaId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, MultimediaId $multimediaId): bool;
}
