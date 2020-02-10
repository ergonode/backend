<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\ImportId;

/**
 */
interface Magento1ProcessorStepInterface
{
    /**
     * @param ImportId $id
     * @param string[] $rows
     * @param Language $language
     */
    public function process(ImportId $id, array $rows, Language $language): void;
}
