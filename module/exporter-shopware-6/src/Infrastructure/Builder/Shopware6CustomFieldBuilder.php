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
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;

class Shopware6CustomFieldBuilder
{
    /**
     * @var Shopware6CustomFieldMapperInterface[]
     */
    private array $collection;

    public function __construct(Shopware6CustomFieldMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        Shopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6CustomField {

        foreach ($this->collection as $mapper) {
            $shopware6CustomField = $mapper->map($channel, $export, $shopware6CustomField, $attribute, $language);
        }

        return $shopware6CustomField;
    }
}
