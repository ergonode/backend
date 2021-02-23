<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Template;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\ExporterFile\Infrastructure\Builder\TemplateElementBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;

class LabelTemplateElementBuilder implements TemplateElementBuilderInterface
{
    public function header(): array
    {
        return ['label'];
    }

    public function build(TemplateElementInterface $element, ExportLineData $data): void
    {
        if ($element instanceof UiTemplateElement) {
            $data->set('label', $element->getLabel());
        }
    }
}
