<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Synchronizer\AbstractPropertyGroupSynchronizer;

/**
 */
class PropertyGroupTextAreaSynchronizer extends AbstractPropertyGroupSynchronizer
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractTextareaAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function process(Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $this->checkExistOrCreate($channel, $attribute);
    }
}
