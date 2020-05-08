<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\ExportProfile;

use Ergonode\Exporter\Infrastructure\Provider\ExportProfileInterface;

/**
 */
class Shopware6ExportProfile implements ExportProfileInterface
{
    public const TYPE = 'shopware-6-api';

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
