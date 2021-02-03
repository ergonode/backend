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

    public function getProfileInfo(Language $language): array;

    public function getInformation(ImportId $id, Language $language): array;

    /**
     * @return ImportId[]
     */
    public function getImportIdsBySourceId(SourceId $sourceId): array;

    public function getSourceTypeByImportId(ImportId $importId): ?string;

    public function getFileNameByImportId(ImportId $importId): ?string;

    /**
     * @return ImportId[]
     */
    public function findActiveImport(SourceId $sourceId): array;
}
