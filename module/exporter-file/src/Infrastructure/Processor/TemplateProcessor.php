<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;

class TemplateProcessor
{
    public function process(FileExportChannel $channel, Template $template): ExportData
    {
        $data = new ExportData();
        $data->add($this->getTemplate($template));

        return $data;
    }

    private function getTemplate(Template $template): ExportLineData
    {
        $result = new ExportLineData();
        $result->set('_name', $template->getName());

        return $result;
    }
}
