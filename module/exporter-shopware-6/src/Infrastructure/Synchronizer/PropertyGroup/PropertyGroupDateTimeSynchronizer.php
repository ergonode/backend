<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractDateAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Synchronizer\AbstractPropertyGroupSynchronizer;

/**
 */
class PropertyGroupDateTimeSynchronizer extends AbstractPropertyGroupSynchronizer
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractDateAttribute::TYPE;
    }
}
