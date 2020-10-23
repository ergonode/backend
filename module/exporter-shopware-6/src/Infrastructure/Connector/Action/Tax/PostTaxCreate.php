<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Tax;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostTaxCreate extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/tax';

    private Shopware6Tax $tax;

    public function __construct(Shopware6Tax $tax)
    {
        $this->tax = $tax;
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
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->tax, 'json');
    }

    private function getUri(): string
    {
        return self::URI;
    }
}
