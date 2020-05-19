<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Configuration\AbstractChannelConfiguration;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface ChannelConfigurationRepositoryInterface
{
    /**
     * @param ChannelId $id
     *
     * @return AbstractChannelConfiguration|null
     *
     * @throws \ReflectionException
     */
    public function load(ChannelId $id): ?AbstractChannelConfiguration;

    /**
     * @param AbstractChannelConfiguration $channelConfiguration
     */
    public function save(AbstractChannelConfiguration $channelConfiguration): void;

    /**
     * @param ChannelId $id
     *
     * @return bool
     */
    public function exists(ChannelId $id): bool;

    /**
     * @param AbstractChannelConfiguration $channelConfiguration
     */
    public function delete(AbstractChannelConfiguration $channelConfiguration): void;
}
