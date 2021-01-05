<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportErrorId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

interface ImportQueryInterface
{
    /**
     * @return array
     */
    public function getLineContent(ImportErrorId $id): array;

    public function getDataSet(SourceId $id): DataSetInterface;

    public function getErrorDataSet(ImportId $id, Language $language): DataSetInterface;

    public function getInformation(ImportId $id, Language $language): array;

    /**
     * @return string[]
     */
    public function getFileNamesBySourceId(SourceId $sourceId): array;
}
