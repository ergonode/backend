<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;

interface ErgonodeProcessorStepInterface
{
    public function __invoke(Import $import, ErgonodeZipSource $source, string $directory): void;
}
