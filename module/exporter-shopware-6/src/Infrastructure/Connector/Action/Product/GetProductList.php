<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetProductList extends AbstractAction
{
    private const URI = '/api/v2/product?%s';

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
     * @return array
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (count($data['data']) > 0) {
            foreach ($data['data'] as $row) {
                $properties = null;
                $options = null;
                $price = null;

                if ($row['attributes']['price']) {
                    foreach ($row['attributes']['price'] as $attributePrice) {
                        $price[] = new Shopware6ProductPrice(
                            $attributePrice['currencyId'],
                            $attributePrice['net'],
                            $attributePrice['gross'],
                            $attributePrice['linked']
                        );
                    }
                }

                if ($row['attributes']['propertyIds']) {
                    foreach ($row['attributes']['propertyIds'] as $propertyId) {
                        $properties[] = [
                            'id' => $propertyId,
                        ];
                    }
                }

                if ($row['attributes']['optionIds']) {
                    foreach ($row['attributes']['optionIds'] as $optionId) {
                        $options[] = [
                            'id' => $optionId,
                        ];
                    }
                }
                $customFields = $row['attributes']['customFields'] ?: null;

                $result[] = new Shopware6Product(
                    $row['id'],
                    $row['attributes']['productNumber'],
                    $row['attributes']['name'],
                    $row['attributes']['description'] ?? null,
                    $properties,
                    $customFields,
                    $row['attributes']['parentId'] ?? null,
                    $options,
                    $row['attributes']['active'],
                    $row['attributes']['stock'] ?? null,
                    $row['attributes']['taxId'] ?? null,
                    $price,
                    $row['attributes']['coverId'] ?? null,
                    $row['attributes']['metaTitle'] ?? null,
                    $row['attributes']['metaDescription'] ?? null,
                    $row['attributes']['keywords'] ?? null
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
