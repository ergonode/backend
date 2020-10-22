<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostMediaFolder extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/media-folder?%s';
    /**
     * @var string
     */
    private string $mediaFolder;

    /**
     * @var bool
     */
    private bool $response;

    /**
     * @param string $mediaFolder
     * @param bool   $response
     */
    public function __construct(string $mediaFolder, bool $response = false)
    {
        $this->mediaFolder = $mediaFolder;
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
     * @return array|object|string|null
     */
    public function parseContent(?string $content)
    {
        return null;
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
                'useParentConfiguration' => false,
                'name' => $this->mediaFolder,
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
