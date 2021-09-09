<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;

interface SourceImportProcessorInterface
{
    public function supported(string $type): bool;

    /**
     * @throw ImportException
     */
    public function start(Import $import): void;
}
