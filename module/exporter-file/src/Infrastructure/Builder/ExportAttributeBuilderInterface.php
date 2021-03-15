<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

interface ExportAttributeBuilderInterface
{
    public function header(): array;

    public function build(AbstractAttribute $attribute, ExportLineData $line, Language $language): void;
}
