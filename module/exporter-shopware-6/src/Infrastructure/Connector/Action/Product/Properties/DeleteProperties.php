<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Properties;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DeleteProperties extends AbstractAction
{
    private const URI = '/api/v2/product/%s/properties/%s';

    private string $productId;

    private string $propertyId;

    public function __construct(string $productId, string $propertyId)
    {
        $this->productId = $productId;
        $this->propertyId = $propertyId;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_DELETE,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productId, $this->propertyId);
    }
}
