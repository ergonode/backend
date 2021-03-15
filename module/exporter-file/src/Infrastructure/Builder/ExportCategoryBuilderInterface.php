<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\Entity\AbstractCategory;

interface ExportCategoryBuilderInterface
{
    public function header(): array;

    public function build(AbstractCategory $category, ExportLineData $result, Language $language): void;
}
