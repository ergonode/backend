<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

interface ChannelQueryInterface
{
    public function getDataSet(Language $language): DataSetInterface;

    public function findChannelIdsByType(string $type): array;
}
