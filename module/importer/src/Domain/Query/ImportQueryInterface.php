<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

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
     * @param SourceId $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(SourceId $id): DataSetInterface;

    /**
     * @param ImportId $id
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getErrorDataSet(ImportId $id, Language $language): DataSetInterface;
}
