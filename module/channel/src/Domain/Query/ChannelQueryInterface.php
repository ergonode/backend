<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Query;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface ChannelQueryInterface
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @param ChannelId $channelId
     *
     * @return array|null
     */
    public function findOneById(ChannelId $channelId): ?array;
}
