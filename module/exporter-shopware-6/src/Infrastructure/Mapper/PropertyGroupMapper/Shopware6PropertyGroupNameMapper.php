<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\PropertyGroupMapper;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6PropertyGroupMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;

/**
 */
class Shopware6PropertyGroupNameMapper implements Shopware6PropertyGroupMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Shopware6PropertyGroup $shopware6PropertyGroup,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6PropertyGroup {
        $name = $attribute->getLabel()->get($language ?: $channel->getDefaultLanguage());
        $shopware6PropertyGroup->setName($name);

        return $shopware6PropertyGroup;
    }
}
