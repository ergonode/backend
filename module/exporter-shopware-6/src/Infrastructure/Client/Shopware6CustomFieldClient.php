<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\GetCustomField;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\GetCustomFieldList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PatchCustomFieldAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PostCustomFieldAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6InstanceOfException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;

class Shopware6CustomFieldClient
{
    private Shopware6Connector $connector;

    private Shopware6CustomFieldRepositoryInterface $repository;

    public function __construct(Shopware6Connector $connector, Shopware6CustomFieldRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    public function find(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        ?Shopware6Language $shopware6Language = null
    ): ?AbstractShopware6CustomField {

        $query = new Shopware6QueryBuilder();
        $query
            ->equals('name', $attribute->getCode()->getValue())
            ->sort('createdAt', 'DESC')
            ->limit(1);

        $action = new GetCustomFieldList($query);

        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $customFieldList = $this->connector->execute($channel, $action);

        if (count($customFieldList) > 0) {
            $customField = reset($customFieldList);

            $this->repository->save(
                $channel->getId(),
                $attribute->getId(),
                $customField->getId(),
                $attribute->getType()
            );

            return $customField;
        }

        return null;
    }

    public function get(
        Shopware6Channel $channel,
        string $shopwareId,
        ?Shopware6Language $shopware6Language = null
    ): ?AbstractShopware6CustomField {
        $action = new GetCustomField($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        return $this->connector->execute($channel, $action);
    }

    /**
     * @throws Shopware6InstanceOfException
     */
    public function insert(
        Shopware6Channel $channel,
        AbstractShopware6CustomField $customField,
        AbstractAttribute $attribute
    ): ?AbstractShopware6CustomField {
        $action = new PostCustomFieldAction($customField, true);

        $shopwareCustomField = $this->connector->execute($channel, $action);

        if (!$shopwareCustomField instanceof AbstractShopware6CustomField) {
            throw new Shopware6InstanceOfException(AbstractShopware6CustomField::class);
        }
        $this->repository->save(
            $channel->getId(),
            $attribute->getId(),
            $shopwareCustomField->getId(),
            $attribute->getType()
        );

        return $shopwareCustomField;
    }

    public function update(
        Shopware6Channel $channel,
        AbstractShopware6CustomField $customField,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchCustomFieldAction($customField);

        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        $this->connector->execute($channel, $action);
    }
}
