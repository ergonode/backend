<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ImportErrorGridQueryInterface
{
    public function getGridQuery(ImportId $id, Language $language): QueryBuilder;
}
