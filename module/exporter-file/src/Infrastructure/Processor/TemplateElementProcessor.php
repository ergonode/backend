<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\ExporterFile\Infrastructure\Processor\Strategy\TemplateElementMapProvider;

class TemplateElementProcessor
{
    private TemplateElementMapProvider $provider;

    public function __construct(TemplateElementMapProvider $provider)
    {
        $this->provider = $provider;
    }

    public function process(
        FileExportChannel $channel,
        Template $template,
        TemplateElementInterface $element
    ): ExportData {
        try {
            $data = new ExportData();
            $data->set($this->getElement($template, $element));

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for template element %s', $template->getName()),
                $exception
            );
        }
    }

    private function getElement(Template $template, TemplateElementInterface $element): LanguageData
    {
        $mapper = $this->provider->provide($element);

        $result = new LanguageData();
        $result->set('_name', $template->getName());
        $result->set('_type', $element->getType());
        $result->set('_x', (string) $element->getPosition()->getX());
        $result->set('_y', (string) $element->getPosition()->getY());
        $result->set('_width', (string) $element->getSize()->getWidth());
        $result->set('_height', (string) $element->getSize()->getHeight());
        $result->set('_properties', json_encode($mapper->map($element), JSON_THROW_ON_ERROR));

        return $result;
    }
}
