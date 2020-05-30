<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

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
     * @param ExportId                                $id
     * @param AbstractExportProfile|FileExportProfile $profile
     * @param AbstractProduct                         $product
     *
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractExportProfile $profile, AbstractProduct $product): void
    {
        try {
            $writer = $this->provider->provide($profile->getFormat());
            $languages = $this->languageQuery->getActive();
            $attributes = array_values($this->attributeQuery->getDictionary());
            sort($attributes);

            $filename = sprintf('%s.%s', $id->getValue(), $writer->getType());

            $this->storage->open($filename);
            $this->storage->append($writer->write($product, $languages, $attributes));
            $this->storage->close();
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $product->getSku()->getValue()),
                $exception
            );
        }
    }
}
