<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Attribute;

use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;

class ExportTextareaAttributeBuilder implements ExportAttributeBuilderInterface
{
    public function header(): array
    {
        return ['rich_edit'];
    }

    public function build(AbstractAttribute $attribute, ExportLineData $line, Language $language): void
    {
        $line->set('rich_edit');
        if ($attribute instanceof AbstractTextareaAttribute) {
            $line->set('rich_edit', (string) $attribute->isRichEdit());
        }
    }
}
