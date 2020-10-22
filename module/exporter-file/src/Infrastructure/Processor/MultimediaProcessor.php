<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

class MultimediaProcessor
{
    /**
     * @param FileExportChannel  $channel
     * @param AbstractMultimedia $multimedia
     *
     * @return ExportData
     *
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractMultimedia $multimedia): ExportData
    {
        try {
            $data = new ExportData();

            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($multimedia, $language), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $multimedia->getId()->getValue()),
                $exception
            );
        }
    }

    /**
     * @param AbstractMultimedia $multimedia
     * @param Language           $language
     *
     * @return LanguageData
     */
    private function getLanguage(AbstractMultimedia $multimedia, Language $language): LanguageData
    {
        $result = new LanguageData();
        $result->set('_id', $multimedia->getId()->getValue());
        $result->set('_language', $language->getCode());
        $result->set('_name', $multimedia->getName());
        $result->set('_filename', $multimedia->getFileName());
        $result->set('_extension', $multimedia->getExtension());
        $result->set('_mime', $multimedia->getMime());
        $result->set('_alt', $multimedia->getAlt()->get($language));
        $result->set('_size', (string) $multimedia->getSize());

        return $result;
    }
}
