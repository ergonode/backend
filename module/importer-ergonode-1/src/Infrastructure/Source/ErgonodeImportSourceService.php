<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Source;

use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;

class ErgonodeImportSourceService implements ImportSourceInterface
{
    public const TYPE = 'ergonode-zip';

    public static function getType(): string
    {
        return self::TYPE;
    }

    public function supported(string $type): bool
    {
        return self::TYPE === $type;
    }
}
