<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Process;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;

/**
 */
class ProcessFileExportProcess
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $languageQuery;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param LanguageQueryInterface  $languageQuery
     * @param WriterProvider          $provider
     * @param FileStorage             $storage
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        LanguageQueryInterface $languageQuery,
        WriterProvider $provider,
        FileStorage $storage
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->languageQuery = $languageQuery;
        $this->provider = $provider;
        $this->storage = $storage;
    }

    /**
     * @param AbstractExportProfile|FileExportProfile $profile
     * @param AbstractProduct                         $product
     */
    public function process(AbstractExportProfile $profile, AbstractProduct $product): void
    {
        $writer = $this->provider->provide($profile->getFormat());
        $languages = $this->languageQuery->getActive();
        $attributes = array_values($this->attributeQuery->getDictionary());
        sort($attributes);

        $filename = sprintf('export.%s', $writer->getType());

        $this->storage->open($filename);
        $this->storage->append($writer->write($product, $languages, $attributes));
        $this->storage->close();
    }
}
