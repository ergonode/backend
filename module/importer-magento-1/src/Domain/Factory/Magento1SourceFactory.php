<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Domain\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Importer\Domain\Factory\SourceFactoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class Magento1SourceFactory implements SourceFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @param SourceId $sourceId
     * @param string   $name
     * @param array    $configuration
     *
     * @return AbstractSource
     */
    public function create(SourceId $sourceId, string $name, array $configuration = []): AbstractSource
    {
        $languages = [];
        foreach ($configuration['languages'] as $key => $language) {
            $languages[$key] = new Language($language);
        }
        $defaultLanguage = new Language($configuration['defaultLanguage']);
        $host = $configuration['host'];
        $import = $configuration['import'];
        
        return new Magento1CsvSource($sourceId, $name, $defaultLanguage, $host, $languages, $import);
    }
}
