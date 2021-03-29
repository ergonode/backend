<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\AssignedProduct;
use Ergonode\ExporterShopware6\Infrastructure\Model\ProductCrossSelling\AbstractAssignedProduct;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetAssignedProductsAction extends AbstractAction
{
    private const URI = '/api/v2/product-cross-selling/%s/assigned-products';

    private string $productCrossSelling;

    public function __construct(string $productCrossSelling)
    {
        $this->productCrossSelling = $productCrossSelling;
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
     * @return null|AbstractAssignedProduct[]
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?array
    {
        if (null === $content) {
            return null;
        }
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (count($data['data']) > 0) {
            $result = [];
            foreach ($data['data'] as $row) {
                $result[] = new AssignedProduct(
                    $row['id'],
                    $row['attributes']['productId'],
                    $row['attributes']['position']
                );
            }

            return $result;
        }

        return null;
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productCrossSelling);
    }
}
