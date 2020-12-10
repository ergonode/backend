<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;

interface ErgonodeProcessorStepInterface
{
    public function __invoke(Import $import, string $directory): void;
}
