<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCustomFieldList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field?%s';

    private Shopware6QueryBuilder $query;

    public function __construct(Shopware6QueryBuilder $query)
    {
        $this->query = $query;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @return Shopware6CustomField[]
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (count($data['data']) > 0) {
            foreach ($data['data'] as $row) {
                $config = new Shopware6CustomFieldConfig(
                    $row['attributes']['config']['type'] ?? null,
                    $row['attributes']['config']['customFieldType'] ?? null,
                    $row['attributes']['config']['label'] ?? null,
                    $row['attributes']['config']['componentName'] ?? null,
                    $row['attributes']['config']['dateType'] ?? null,
                    $row['attributes']['config']['numberType'] ?? null,
                    $row['attributes']['config']['options'] ?? null
                );

                $result[] = new Shopware6CustomField(
                    $row['id'],
                    $row['attributes']['name'],
                    $row['attributes']['type'],
                    $config,
                    $row['attributes']['customFieldSetId']
                );
            }
        }

        return $result;
    }

    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->query->getQuery()), '?');
    }
}
