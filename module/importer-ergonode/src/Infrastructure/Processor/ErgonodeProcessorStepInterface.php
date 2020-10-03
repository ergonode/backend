<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Ergonode\Transformer\Domain\Entity\Transformer;

/**
 */
interface ErgonodeProcessorStepInterface
{
    /**
     * @param Import            $import
     * @param ProductModel      $product
     * @param Transformer       $transformer
     * @param ErgonodeCsvSource $source
     */
    public function process(
        Import $import,
        ProductModel $product,
        Transformer $transformer,
        ErgonodeCsvSource $source
    ): void;
}
