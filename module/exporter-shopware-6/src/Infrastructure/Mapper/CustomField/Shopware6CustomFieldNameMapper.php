<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;

class Shopware6CustomFieldNameMapper implements Shopware6CustomFieldMapperInterface
{
    /**
     * @param Shopware6Channel     $channel
     * @param Shopware6CustomField $shopware6CustomField
     * @param AbstractAttribute    $attribute
     * @param Language|null        $language
     *
     * @return Shopware6CustomField
     */
    public function map(
        Shopware6Channel $channel,
        Shopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6CustomField {
        $shopware6CustomField->setName($attribute->getCode()->getValue());

        return $shopware6CustomField;
    }
}
