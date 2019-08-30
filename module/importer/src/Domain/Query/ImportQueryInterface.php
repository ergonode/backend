<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\Importer\Domain\Entity\ImportLineId;

/**
 */
interface ImportQueryInterface
{
    /**
     * @param ImportLineId $id
     *
     * @return array
     */
    public function getLineContent(ImportLineId $id): array;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
