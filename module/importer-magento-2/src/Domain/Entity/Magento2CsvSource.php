<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Domain\Entity;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

/**
 */
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

    /**
     * @param SourceId $id
     * @param array    $configuration
     */
    public function __construct(SourceId $id, array $configuration = [])
    {
        parent::__construct($id);

        $this->configuration = array_merge(self::DEFAULT,$configuration);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->configuration['file'];
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->configuration[self::DELIMITER];
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->configuration[self::ENCLOSURE];
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->configuration[self::ESCAPE];
    }
}
