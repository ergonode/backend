<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;

class Shopware6CustomFieldClient
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6CustomFieldRepositoryInterface
     */
    private Shopware6CustomFieldRepositoryInterface $repository;

    /**
     * @param Shopware6Connector                      $connector
     * @param Shopware6CustomFieldRepositoryInterface $repository
     */
    public function __construct(Shopware6Connector $connector, Shopware6CustomFieldRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param AbstractAttribute      $attribute
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6CustomField|null
     */
    public function find(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6CustomField {

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

    /**
     * @param Shopware6Channel       $channel
     * @param string                 $shopwareId
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6CustomField|null
     */
    public function get(
        Shopware6Channel $channel,
        string $shopwareId,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6CustomField {
        $action = new GetCustomField($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel     $channel
     * @param Shopware6CustomField $customField
     * @param AbstractAttribute    $attribute
     *
     * @return Shopware6CustomField|null
     */
    public function insert(
        Shopware6Channel $channel,
        Shopware6CustomField $customField,
        AbstractAttribute $attribute
    ): ?Shopware6CustomField {
        $action = new PostCustomFieldAction($customField, true);

        $shopwareCustomField = $this->connector->execute($channel, $action);
        $this->repository->save(
            $channel->getId(),
            $attribute->getId(),
            $shopwareCustomField->getId(),
            $attribute->getType()
        );

        return $shopwareCustomField;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6CustomField   $customField
     * @param Shopware6Language|null $shopware6Language
     */
    public function update(
        Shopware6Channel $channel,
        Shopware6CustomField $customField,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchCustomFieldAction($customField);

        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        $this->connector->execute($channel, $action);
    }
}
