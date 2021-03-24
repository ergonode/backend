<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractProductCustomFieldSetMapper;

class ProductCustomFieldSetNumericMapper extends AbstractProductCustomFieldSetMapper
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractNumericAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getValue(Shopware6Channel $channel, AbstractAttribute $attribute, $calculateValue): string
    {
        return (string) $calculateValue;
    }
}
