<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;

class TemplateProcessor
{
    public function process(FileExportChannel $channel, Template $template): ExportData
    {
        $data = new ExportData();
        $data->set($this->getTemplate($template));

        return $data;
    }

    private function getTemplate(Template $template): LanguageData
    {
        $result = new LanguageData();
        $result->set('_name', $template->getName());

        return $result;
    }
}
