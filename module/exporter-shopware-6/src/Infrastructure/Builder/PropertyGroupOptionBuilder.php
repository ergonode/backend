<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\PropertyGroupOptionMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;

class PropertyGroupOptionBuilder
{
    /**
     * @var PropertyGroupOptionMapperInterface[]
     */
    private array $collection;

    public function __construct(PropertyGroupOptionMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        Shopware6PropertyGroupOption $propertyGroupOption,
        AbstractOption $option,
        ?Language $language = null
    ): Shopware6PropertyGroupOption {
        foreach ($this->collection as $mapper) {
            $propertyGroupOption = $mapper->map($channel, $export, $propertyGroupOption, $option, $language);
        }

        return $propertyGroupOption;
    }
}
