<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;

interface ExportProductBuilderInterface
{
    public function header(): array;

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void;
}
