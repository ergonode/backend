<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Synchronizer\AbstractCustomFieldSynchronizer;

/**
 */
class CustomFieldDateTimeSynchronizer extends AbstractCustomFieldSynchronizer
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return AbstractNumericAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    protected function getMapping(Shopware6Channel $channel, AbstractAttribute $attribute): array
    {
        $code = $attribute->getCode()->getValue();

        return
            [
                'name' => $code,
                'type' => 'datetime',
                'config' => [
                    'type' => 'date',
                    'customFieldType' => 'date',
                    'dateType' => 'datetime',
                ],
            ];
    }
}
