<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\PropertyGroup;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6PropertyGroupMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;

class Shopware6PropertyGroupNameMapper implements Shopware6PropertyGroupMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6PropertyGroup $shopware6PropertyGroup,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6PropertyGroup {
        $name = $attribute->getLabel()->get($language ?: $channel->getDefaultLanguage());
        if ($name) {
            $shopware6PropertyGroup->setName($name);
        }

        if (null === $language && null === $name) {
            $shopware6PropertyGroup->setName($attribute->getCode()->getValue());
        }

        return $shopware6PropertyGroup;
    }
}
