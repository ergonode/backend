<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostPropertyGroupOptionsAction extends AbstractAction
{
    private const URI = '/api/v2/property-group/%s/options?%s';

    private string $propertyGroupId;

    private Shopware6PropertyGroupOption $propertyGroupOption;

    private bool $response;

    public function __construct(
        string $propertyGroupId,
        Shopware6PropertyGroupOption $propertyGroupOption,
        bool $response
    ) {
        $this->propertyGroupId = $propertyGroupId;
        $this->propertyGroupOption = $propertyGroupOption;
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
    public function parseContent(?string $content): ?Shopware6PropertyGroupOption
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6PropertyGroupOption(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['mediaId'],
            $data['data']['attributes']['position']
        );
    }

    private function buildBody(): string
    {
        return json_encode($this->propertyGroupOption->jsonSerialize(), JSON_THROW_ON_ERROR);
    }

    private function getUri(): string
    {
        $query = [];
        if ($this->response) {
            $query['_response'] = 'true';
        }

        return rtrim(sprintf(self::URI, $this->propertyGroupId, http_build_query($query)), '?');
    }
}
