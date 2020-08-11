<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Synchronizer\AbstractPropertyGroupSynchronizer;

/**
 */
class PropertyGroupTextSynchronizer extends AbstractPropertyGroupSynchronizer
{
    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractTextAttribute::TYPE;
    }
}
