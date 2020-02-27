<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Source;

use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;

/**
 */
class Magento1ImportSourceService implements ImportSourceInterface
{
    public const TYPE = 'magento-1-csv';

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
