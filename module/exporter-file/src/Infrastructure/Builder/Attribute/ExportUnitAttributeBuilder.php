<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Attribute;

use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractUnitAttribute;
use Webmozart\Assert\Assert;

class ExportUnitAttributeBuilder implements ExportAttributeBuilderInterface
{
    private UnitQueryInterface $unitQuery;

    public function __construct(UnitQueryInterface $unitQuery)
    {
        $this->unitQuery = $unitQuery;
    }

    public function header(): array
    {
        return ['unit'];
    }

    public function build(AbstractAttribute $attribute, ExportLineData $line, Language $language): void
    {
        $line->set('unit');
        if ($attribute instanceof AbstractUnitAttribute) {
            $symbol = $this->unitQuery->findCodeById($attribute->getUnitId());
            Assert::notNull($symbol);

            $line->set('unit', $symbol);
        }
    }
}
