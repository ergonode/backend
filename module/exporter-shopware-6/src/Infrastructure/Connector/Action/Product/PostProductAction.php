<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\CreateShopwareProduct;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostProductAction extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/product';

    /**
     * @var CreateShopwareProduct
     */
    private CreateShopwareProduct $product;

    /**
     * @param CreateShopwareProduct $product
     */
    public function __construct(CreateShopwareProduct $product)
    {
        $this->product = $product;
    }

    /**
     * @return Request
     */
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
     * @param string|null $content
     *
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    /**
     * @return string
     */
    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->product, 'json');
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return self::URI;
    }
}
