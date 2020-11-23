<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6PropertyGroupMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;

class Shopware6PropertyGroupBuilder
{
    /**
     * @var Shopware6PropertyGroupMapperInterface[]
     */
    private array $collection;

    public function __construct(Shopware6PropertyGroupMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        Shopware6PropertyGroup $shopware6PropertyGroup,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6PropertyGroup {

        foreach ($this->collection as $mapper) {
            $shopware6PropertyGroup = $mapper->map($channel, $export, $shopware6PropertyGroup, $attribute, $language);
        }

        return $shopware6PropertyGroup;
    }
}
