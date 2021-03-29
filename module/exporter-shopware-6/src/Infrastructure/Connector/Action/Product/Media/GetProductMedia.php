<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetProductMedia extends AbstractAction
{
    private const URI = '/api/v2/product/%s/media';

    private string $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
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
     * @return Shopware6ProductMedia[]|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?array
    {
        $result = null;
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (count($data['data']) > 0) {
            foreach ($data['data'] as $row) {
                $result[] = new Shopware6ProductMedia(
                    $row['id'],
                    $row['attributes']['mediaId'],
                    $row['attributes']['position']
                );
            }
        }

        return $result;
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productId);
    }
}
