<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PatchCrossSellingAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/product-cross-selling/%s';

    private AbstractProductCrossSelling $productCrossSelling;

    public function __construct(AbstractProductCrossSelling $productCrossSelling)
    {
        $this->productCrossSelling = $productCrossSelling;
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
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->productCrossSelling, 'json');
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productCrossSelling->getId());
    }
}
