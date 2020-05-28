<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface ExportQueryInterface
{
    /**
     * @param ChannelId $channelId
     * @param Language  $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ChannelId $channelId, Language $language): DataSetInterface;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getProfileInfo(Language $language): array;
}
