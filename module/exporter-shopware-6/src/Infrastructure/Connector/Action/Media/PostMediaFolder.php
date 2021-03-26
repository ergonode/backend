<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostMediaFolder extends AbstractAction
{
    private const URI = '/api/v2/media-folder?%s';
    private string $mediaFolder;

    private bool $response;

    public function __construct(string $mediaFolder, bool $response = false)
    {
        $this->mediaFolder = $mediaFolder;
        $this->response = $response;
    }

    /**
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
     * @return array|object|string|null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    /**
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

    private function getUri(): string
    {
        $query = [];
        if ($this->response) {
            $query['_response'] = 'true';
        }

        return rtrim(sprintf(self::URI, http_build_query($query)), '?');
    }
}
