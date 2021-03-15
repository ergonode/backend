<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Template;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;

class ExportLabelTemplateElementBuilder implements ExportTemplateElementBuilderInterface
{
    public function header(): array
    {
        return ['label'];
    }

    public function build(TemplateElementInterface $element, ExportLineData $data, Language $language): void
    {
        $data->set('label');
        if ($element instanceof UiTemplateElement) {
            $data->set('label', $element->getLabel());
        }
    }
}
