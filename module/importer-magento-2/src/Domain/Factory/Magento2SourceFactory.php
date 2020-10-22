<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Domain\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;

class Magento2SourceFactory
{
    /**
     * @param SourceId $sourceId
     * @param string   $name
     * @param array    $configuration
     *
     * @return AbstractSource
     */
    public function create(SourceId $sourceId, string $name, array $configuration = []): AbstractSource
    {
        return new Magento2CsvSource($sourceId, $configuration);
    }
}
