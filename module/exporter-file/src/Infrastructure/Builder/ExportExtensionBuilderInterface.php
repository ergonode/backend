<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

interface ExportExtensionBuilderInterface
{
    public function header(): array;

    public function build(AbstractAggregateRoot $aggregate, ExportLineData $line, Language $language): void;
}
