<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CustomFieldQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldClient;

/**
 */
abstract class AbstractCustomFieldOptionSynchronizer extends AbstractCustomFieldSynchronizer
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @param Shopware6CustomFieldClient              $client
     * @param Shopware6CustomFieldQueryInterface      $customFieldQuery
     * @param Shopware6CustomFieldRepositoryInterface $customFieldRepository
     * @param AttributeRepositoryInterface            $attributeRepository
     * @param OptionQueryInterface                    $optionQuery
     */
    public function __construct(
        Shopware6CustomFieldClient $client,
        Shopware6CustomFieldQueryInterface $customFieldQuery,
        Shopware6CustomFieldRepositoryInterface $customFieldRepository,
        AttributeRepositoryInterface $attributeRepository,
        OptionQueryInterface $optionQuery
    ) {
        parent::__construct($client, $customFieldQuery, $customFieldRepository, $attributeRepository);
        $this->optionQuery = $optionQuery;
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     *
     * @return array
     */
    protected function getOptions(Shopware6Channel $channel, AbstractAttribute $attribute): array
    {
        $options = $this->optionQuery->getAll($attribute->getId());
        $result = [];

        foreach ($options as $option) {
            $result[] = [
                'value' => $option['code'],
                'label' => $this->getLabel($channel, $option),
            ];
        }

        return $result;
    }

    /**
     * @param Shopware6Channel $channel
     * @param array            $option
     *
     * @return array
     */
    private function getLabel(Shopware6Channel $channel, array $option): array
    {
        $label = ['en-GB' => $option['code']];

        foreach ($channel->getLanguages() as $language) {
            if (isset($option['label'][$language->getCode()])) {
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
