<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Doctrine\DBAL\Query\QueryBuilder;

interface AttributeGridQueryInterface
{
    public function getDataSetQuery(Language $language, bool $system = false): QueryBuilder;
}
