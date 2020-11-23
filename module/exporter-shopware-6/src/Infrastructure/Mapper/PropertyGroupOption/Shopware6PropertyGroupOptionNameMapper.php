<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\PropertyGroupOption;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6PropertyGroupOptionMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;

class Shopware6PropertyGroupOptionNameMapper implements Shopware6PropertyGroupOptionMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6PropertyGroupOption $propertyGroupOption,
        AbstractOption $option,
        ?Language $language = null
    ): Shopware6PropertyGroupOption {
        $name = $option->getLabel()->get($language ?: $channel->getDefaultLanguage());
        if ($name) {
            $propertyGroupOption->setName($name);
        }

        if (null === $language && null === $name) {
            $propertyGroupOption->setName($option->getCode()->getValue());
        }


        return $propertyGroupOption;
    }
}
