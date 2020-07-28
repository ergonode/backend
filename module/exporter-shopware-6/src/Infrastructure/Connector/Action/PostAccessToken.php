<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class PostAccessToken extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/oauth/token';

    /**
     * @var Shopware6Channel
     */
    private Shopware6Channel $channel;

    /**
     * @param Shopware6Channel $channel
     */
    public function __construct(Shopware6Channel $channel)
    {
        $this->channel = $channel;
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
     * @param $content
     *
     * @return string
     */
    public function parseContent($content): string
    {
        $data = json_decode($content, true);

        return $data['access_token'];
    }

    /**
     * @return string
     */
    private function buildBody(): string
    {
        return json_encode(
            [
                'client_id' => $this->channel->getClientId(),
                'client_secret' => $this->channel->getClientKey(),
                'grant_type' => 'client_credentials',
            ]
        );
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return self::URI;
    }
}
