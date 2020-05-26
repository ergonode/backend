<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface ExportProfileQueryInterface
{
    /**
     * @return array
     */
    public function getAllExportProfileIds(): array;

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;
}
