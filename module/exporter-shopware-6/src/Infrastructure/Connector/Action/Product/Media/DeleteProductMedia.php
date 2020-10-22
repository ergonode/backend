<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DeleteProductMedia extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/product/%s/media/%s';

    /**
     * @var string
     */
    private string $productId;

    /**
     * @var string
     */
    private string $mediaId;

    /**
     * @param string $productId
     * @param string $mediaId
     */
    public function __construct(string $productId, string $mediaId)
    {
        $this->productId = $productId;
        $this->mediaId = $mediaId;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_DELETE,
            $this->getUri(),
            $this->buildHeaders()
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
    private function getUri(): string
    {
        return sprintf(self::URI, $this->productId, $this->mediaId);
    }
}
