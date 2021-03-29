<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\ProductCrossSelling;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCrossSellingAction extends AbstractAction
{
    private const URI = '/api/v2/product-cross-selling/%s';

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
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?ProductCrossSelling
    {
        if (null === $content) {
            return null;
        }
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new ProductCrossSelling(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['productId'],
            $data['data']['attributes']['active'],
            $data['data']['attributes']['type']
        );
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productCrossSelling);
    }
}
