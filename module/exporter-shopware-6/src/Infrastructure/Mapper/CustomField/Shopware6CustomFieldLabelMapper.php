<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;

class Shopware6CustomFieldLabelMapper implements Shopware6CustomFieldMapperInterface
{
    private Shopware6LanguageRepositoryInterface  $languageRepository;

    public function __construct(Shopware6LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): AbstractShopware6CustomField {

        $label = [
            str_replace('_', '-', $channel->getDefaultLanguage()->getCode()) => $attribute
                ->getCode()
                ->getValue(),
        ];

        foreach ($channel->getLanguages() as $lang) {
            if ($attribute->getLabel()->has($lang)
                && $this->languageRepository->exists($channel->getId(), $lang->getCode())) {
                $label[str_replace('_', '-', $lang->getCode())] = $attribute->getLabel()->get($lang);
            }
        }
        if ($attribute->getLabel()->has($channel->getDefaultLanguage())) {
            $label[str_replace('_', '-', $channel->getDefaultLanguage()->getCode())] = $attribute
                ->getLabel()
                ->get(
                    $channel->getDefaultLanguage()
                );
        }

        $shopware6CustomField->getConfig()->setLabel($label);

        return $shopware6CustomField;
    }
}
