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
                'label' =>
                    array_merge(
                        [
                            'en-GB' => $option['code'],
                        ],
                        $option['label']
                    ),
            ];
        }

        return $result;
    }
}
