<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;

class CustomFieldNameMapper implements CustomFieldMapperInterface
{
    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): AbstractShopware6CustomField {
        $shopware6CustomField->setName($attribute->getCode()->getValue());

        return $shopware6CustomField;
    }
}
