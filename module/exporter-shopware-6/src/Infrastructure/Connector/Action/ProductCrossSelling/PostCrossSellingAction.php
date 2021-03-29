<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\ProductCrossSelling;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCrossSellingAction extends AbstractAction
{
    private const URI = '/api/v2/product-cross-selling?%s';

    private AbstractProductCrossSelling $productCrossSelling;

    private bool $response;

    public function __construct(AbstractProductCrossSelling $productCrossSelling, bool $response = false)
    {
        $this->productCrossSelling = $productCrossSelling;
        $this->response = $response;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_POST,
            $this->getUri(),
            $this->buildHeaders(),
            $this->buildBody()
        );
    }

    /**
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?AbstractProductCrossSelling
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

    private function buildBody(): string
    {
        return json_encode($this->productCrossSelling->jsonSerialize(), JSON_THROW_ON_ERROR);
    }

    private function getUri(): string
    {
        $query = [];
        if ($this->response) {
            $query['_response'] = 'true';
        }

        return rtrim(sprintf(self::URI, http_build_query($query)), '?');
    }
}
