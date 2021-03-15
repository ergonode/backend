<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Source;

use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;

class Magento1ImportSourceService implements ImportSourceInterface
{
    public const TYPE = 'magento-1-csv';

    public static function getType(): string
    {
        return self::TYPE;
    }

    public function supported(string $type): bool
    {
        return self::TYPE === $type;
    }
}
