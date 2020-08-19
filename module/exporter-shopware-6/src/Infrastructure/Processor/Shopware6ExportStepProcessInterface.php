<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
interface Shopware6ExportStepProcessInterface
{
    /**
     * @param ExportId $exportId
     */
    public function export(ExportId $exportId): void;
}
