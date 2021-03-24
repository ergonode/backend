<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ExportGridQueryInterface
{
    public function getGridQuery(ChannelId $channelId, Language $language): QueryBuilder;
}
