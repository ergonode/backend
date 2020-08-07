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
use Ergonode\Attribute\Domain\Entity\AbstractOption;

/**
 */
class OptionProcessor
{
    /**
     * @param FileExportChannel $channel
     * @param AbstractOption    $option
     *
     * @return ExportData
     *
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractOption $option): ExportData
    {
        try {
            $data = new ExportData();

            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($option, $language), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $option->getCode()->getValue()),
                $exception
            );
        }
    }

    /**
     * @param AbstractOption $option
     * @param Language       $language
     *
     * @return LanguageData
     */
    private function getLanguage(AbstractOption $option, Language $language): LanguageData
    {
        $result = new LanguageData();
        $result->set('_id', $option->getId()->getValue());
        $result->set('_code', $option->getCode()->getValue());
        $result->set('_attribute', $option->getAttributeId()->getValue());
        $result->set('_language', $language->getCode());
        $result->set('_label', $option->getLabel()->get($language));

        return $result;
    }
}
