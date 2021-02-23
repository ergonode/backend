<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class CategoryProcessor
{
    /**
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractCategory $category): ExportData
    {
        try {
            $data = new ExportData();

            foreach ($channel->getLanguages() as $language) {
                $data->add($this->getLanguage($category, $language));
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $category->getCode()->getValue()),
                $exception
            );
        }
    }

    private function getLanguage(AbstractCategory $category, Language $language): ExportLineData
    {
        $result = new ExportLineData();
        $result->set('_code', $category->getCode()->getValue());
        $result->set('_name', $category->getName()->get($language));
        $result->set('_language', $language->getCode());

        return $result;
    }
}
