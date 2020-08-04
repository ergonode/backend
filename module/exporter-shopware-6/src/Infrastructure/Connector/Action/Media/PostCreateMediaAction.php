<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Media;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostCreateMediaAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/media?%s';

    /**
     * @var string
     */
    private string $mediaFolderId;

    /**
     * @var bool
     */
    private bool $response;

    /**
     * @param string $mediaFolderId
     * @param bool   $response
     */
    public function __construct(string $mediaFolderId, bool $response = false)
    {
        $this->mediaFolderId = $mediaFolderId;
        $this->response = $response;
    }

    /**
     * @return Request
     *
     * @throws \JsonException
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
     * @return Shopware6Media|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?Shopware6Media
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6Media($data['data']['id']);
    }

    /**
     * @return string
     *
     * @throws \JsonException
     */
    private function buildBody(): string
    {
        return json_encode(
            [
                'mediaFolderId' => $this->mediaFolderId,
            ],
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        $query = [];
        if ($this->response) {
            $query['_response'] = 'true';
        }

        return rtrim(sprintf(self::URI, http_build_query($query)), '?');
    }
}
