<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\GetCustomFieldSetList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PostCustomFieldSetAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;

/**
 */
class Shopware6CustomFieldClient
{

    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @param Shopware6Connector $connector
     */
    public function __construct(Shopware6Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     *
     * @return array|null
     */
    public function load(Shopware6ExportApiProfile $profile): ?array
    {
        $action = new GetCustomFieldSetList();

        return $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param Shopware6CustomField      $customField
     */
    public function insert(Shopware6ExportApiProfile $profile, Shopware6CustomField $customField): void
    {
        $action = new PostCustomFieldSetAction($customField);

        $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param string                    $code
     *
     * @return Shopware6CustomField|null
     */
    public function findByCode(Shopware6ExportApiProfile $profile, string $code):?Shopware6CustomField
    {
        $query = [
            [
                'query' => [
                    'type' => 'equals',
                    'field' => 'name',
                    'value' => $code,
                ],
                'sort' => [
                    'field' => 'createdAt',
                    'order' => 'DESC',
                ],
            ],
        ];
        $action = new GetCustomFieldSetList($query, 1);

        $customFieldList = $this->connector->execute($profile, $action);
        if (is_array($customFieldList) && count($customFieldList) > 0) {
            return $customFieldList[0];
        }

        return null;
    }
}
