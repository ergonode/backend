<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CustomFieldMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;

class Shopware6CustomFieldConfigOptionsMapper implements Shopware6CustomFieldMapperInterface
{
    private OptionQueryInterface $optionQuery;

    private Shopware6LanguageRepositoryInterface  $languageRepository;

    public function __construct(
        OptionQueryInterface $optionQuery,
        Shopware6LanguageRepositoryInterface $languageRepository
    ) {
        $this->optionQuery = $optionQuery;
        $this->languageRepository = $languageRepository;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute,
        ?Language $language = null
    ): Shopware6CustomField {
        $this->getOptions($channel, $shopware6CustomField, $attribute);

        return $shopware6CustomField;
    }

    private function getOptions(
        Shopware6Channel $channel,
        Shopware6CustomField $shopware6CustomField,
        AbstractAttribute $attribute
    ): Shopware6CustomField {
        $options = $this->optionQuery->getAll($attribute->getId());

        foreach ($options as $option) {
            $shopware6CustomField->addOptions($this->getOption($channel, $option));
        }

        return $shopware6CustomField;
    }

    /**
     * @param array $option
     *
     * @return array
     */
    private function getOption(Shopware6Channel $channel, array $option): array
    {
        return [
            'value' => $option['code'],
            'label' => $this->getLabel($channel, $option),
        ];
    }

    /**
     * @param array $option
     *
     * @return array
     */
    private function getLabel(Shopware6Channel $channel, array $option): array
    {
        $label = [
            str_replace('_', '-', $channel->getDefaultLanguage()->getCode()) => $option['code'],
        ];

        foreach ($channel->getLanguages() as $language) {
            if (isset($option['label'][$language->getCode()])
                && $this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $label[str_replace('_', '-', $language->getCode())] = $option['label'][$language->getCode()];
            }
        }

        if (isset($option['label'][$channel->getDefaultLanguage()->getCode()])) {
            $label[str_replace(
                '_',
                '-',
                $channel->getDefaultLanguage()->getCode()
            )] = $option['label'][$channel->getDefaultLanguage()->getCode()];
        }

        return $label;
    }
}
