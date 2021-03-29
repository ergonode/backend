<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostPropertyGroupAction extends AbstractAction
{
    private const URI = '/api/v2/property-group?%s';

    private Shopware6PropertyGroup $propertyGroup;

    private bool $response;

    public function __construct(Shopware6PropertyGroup $propertyGroup, bool $response = false)
    {
        $this->propertyGroup = $propertyGroup;
        $this->response = $response;
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
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?Shopware6PropertyGroup
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6PropertyGroup(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['displayType'],
            $data['data']['attributes']['sortingType']
        );
    }

    private function buildBody(): string
    {
        return json_encode($this->propertyGroup->jsonSerialize(), JSON_THROW_ON_ERROR);
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
