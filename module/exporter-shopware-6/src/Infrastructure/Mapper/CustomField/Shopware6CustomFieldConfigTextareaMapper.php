<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;

class Shopware6CustomFieldConfigTextareaMapper implements Shopware6CustomFieldMapperInterface
{
    private const TYPE = 'text';
    private const CUSTOM_FIELD_TYPE = 'textEditor';
    private const COMPONENT_NAME = 'sw-text-editor';

    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): AbstractShopware6CustomField {

        if ($attribute->getType() === AbstractTextareaAttribute::TYPE) {
            $shopware6CustomField->setType(self::TYPE);
            $shopware6CustomField->getConfig()->setType(self::TYPE);
            $shopware6CustomField->getConfig()->setCustomFieldType(self::CUSTOM_FIELD_TYPE);
            $shopware6CustomField->getConfig()->setComponentName(self::COMPONENT_NAME);
        }

        return $shopware6CustomField;
    }
}
