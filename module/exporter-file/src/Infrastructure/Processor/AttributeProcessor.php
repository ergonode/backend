<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;

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
                $data->add($this->getLanguage($attribute, $language));
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $attribute->getCode()->getValue()),
                $exception
            );
        }
    }

    private function getLanguage(AbstractAttribute $attribute, Language $language): ExportLineData
    {
        $result = new ExportLineData();
        $result->set('_code', $attribute->getCode()->getValue());
        $result->set('_type', $attribute->getType());
        $result->set('_language', $language->getCode());
        $result->set('_name', $attribute->getLabel()->get($language));
        $result->set('_hint', $attribute->getHint()->get($language));
        $result->set('_placeholder', $attribute->getPlaceholder()->get($language));
        $result->set('_scope', $attribute->getScope()->getValue());
        $result->set('_parameters', json_encode($attribute->getParameters(), JSON_THROW_ON_ERROR));

        return $result;
    }
}
