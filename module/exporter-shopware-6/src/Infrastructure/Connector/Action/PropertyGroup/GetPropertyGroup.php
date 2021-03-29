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

class GetPropertyGroup extends AbstractAction
{
    private const URI = '/api/v2/property-group/%s';

    private string $propertyGroupId;

    public function __construct(string $propertyGroupId)
    {
        $this->propertyGroupId = $propertyGroupId;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @throws \JsonException
     */
    public function parseContent(?string $content): Shopware6PropertyGroup
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6PropertyGroup(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['displayType'],
            $data['data']['attributes']['sortingType']
        );
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->propertyGroupId);
    }
}
