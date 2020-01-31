<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Domain\Factory;


use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Source\SourceId;
use Ergonode\Importer\Domain\Factory\SourceFactoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;

/**
 */
class Magento2SourceFactory implements SourceFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento2CsvSource::TYPE;
    }

    /**
     * @param SourceId $sourceId
     * @param string   $filename
     *
     * @return AbstractSource
     */
    public function create(SourceId $sourceId, string $filename): AbstractSource
    {
       return new Magento2CsvSource(
           $sourceId,
           $filename,
       );
    }
}
