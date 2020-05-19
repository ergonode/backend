<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Provider;

use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;

/**
 */
interface ExportProcessorInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param Export $export
     */
    public function run(Export $export): void;
}
