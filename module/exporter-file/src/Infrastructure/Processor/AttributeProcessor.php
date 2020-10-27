<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class AttributeProcessor
{
    /**
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractAttribute $attribute): ExportData
    {
        try {
            $data = new ExportData();

            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($attribute, $language), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $attribute->getCode()->getValue()),
                $exception
            );
        }
    }

    private function getLanguage(AbstractAttribute $attribute, Language $language): LanguageData
    {
        $result = new LanguageData();
        $result->set('_id', $attribute->getId()->getValue());
        $result->set('_code', $attribute->getCode()->getValue());
        $result->set('_type', $attribute->getType());
        $result->set('_language', $language->getCode());
        $result->set('_name', $attribute->getLabel()->get($language));
        $result->set('_hint', $attribute->getHint()->get($language));
        $result->set('_placeholder', $attribute->getPlaceholder()->get($language));

        return $result;
    }
}
