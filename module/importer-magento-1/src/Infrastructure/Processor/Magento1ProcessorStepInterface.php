<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Domain\Entity\Transformer;

/**
 */
interface Magento1ProcessorStepInterface
{
    /**
     * @param Import            $import
     * @param array             $rows
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param Progress          $progress
     *
     * @return int
     */
    public function process(
        Import $import,
        array $rows,
        Transformer $transformer,
        Magento1CsvSource $source,
        Progress $progress
    ): int;
}
