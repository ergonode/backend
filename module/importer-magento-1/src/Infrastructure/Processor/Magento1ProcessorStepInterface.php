<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Transformer\Domain\Entity\Transformer;

/**
 */
interface Magento1ProcessorStepInterface
{
    /**
     * @param Import      $import
     * @param string[]    $rows
     * @param Transformer $transformer
     * @param Language    $language
     */
    public function process(Import $import, array $rows, Transformer $transformer, Language $language): void;
}
