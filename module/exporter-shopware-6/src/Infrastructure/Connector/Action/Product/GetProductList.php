<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\SwagQLBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetProductList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/product?%s';

    /**
     * @var SwagQLBuilder
     */
    private SwagQLBuilder $query;

    /**
     * @param SwagQLBuilder $query
     */
    public function __construct(SwagQLBuilder $query)
    {
        $this->query = $query;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @param string|null $content
     *
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
                $category = null;
                $properties = null;
                $customFields = null;
                $options = null;
                $price = null;

                if ($row['attributes']['categoryTree']) {
                    foreach ($row['attributes']['categoryTree'] as $attributeCategory) {
                        $category[] = [
                            'id' => $attributeCategory,
                        ];
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

                $result[] = new Shopware6Product(
                    $row['id'],
                    $row['attributes']['productNumber'],
                    $row['attributes']['name'],
                    $row['attributes']['description'],
                    $category,
                    $properties,
                    $customFields,
                    $row['attributes']['parentId'],
                    $options,
                    $row['attributes']['active'],
                    $row['attributes']['stock'],
                    $row['attributes']['taxId'],
                    $price
                );
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->query->getQuery()), '?');
    }
}
