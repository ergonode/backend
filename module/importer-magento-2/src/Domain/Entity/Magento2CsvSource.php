<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento2\Domain\Entity;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;

class Magento2CsvSource extends AbstractSource
{
    public const TYPE = 'magento-2-csv';

    public const DELIMITER = 'delimiter';
    public const ENCLOSURE = 'enclosure';
    public const ESCAPE = 'escape';

    public const DEFAULT = [
        self::DELIMITER => ',',
        self::ENCLOSURE => '"',
        self::ESCAPE => '\\',
    ];

    public function getType(): string
    {
        return self::TYPE;
    }
}
