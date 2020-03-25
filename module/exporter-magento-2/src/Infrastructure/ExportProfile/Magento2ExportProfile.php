<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Infrastructure\ExportProfile;

use Ergonode\Exporter\Infrastructure\Provider\ExportProfileInterface;

/**
 */
class Magento2ExportProfile implements ExportProfileInterface
{
    public const TYPE = 'magento-2-csv';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return self::TYPE === $type;
    }
}
