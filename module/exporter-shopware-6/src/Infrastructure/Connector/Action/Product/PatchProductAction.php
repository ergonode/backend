<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PatchProductAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/product/';

    private Shopware6Product $product;

    public function __construct(Shopware6Product $product)
    {
        $this->product = $product;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_PATCH,
            $this->getUri(),
            $this->buildHeaders(),
            $this->buildBody()
        );
    }

    /**
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function buildBody(): string
    {
        return json_encode($this->product->jsonSerialize(), JSON_THROW_ON_ERROR);
    }

    private function getUri(): string
    {
        return self::URI.$this->product->getId();
    }
}
