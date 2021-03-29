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

class GetPropertyGroupOptions extends AbstractAction
{
    private const URI = '/api/v2/property-group/%s/options/%s';

    private string $propertyGroupId;

    private string $propertyGroupOptionId;

    public function __construct(string $propertyGroupId, string $propertyGroupOptionId)
    {
        $this->propertyGroupId = $propertyGroupId;
        $this->propertyGroupOptionId = $propertyGroupOptionId;
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
    public function parseContent(?string $content): Shopware6PropertyGroupOption
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6PropertyGroupOption(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['mediaId'],
            $data['data']['attributes']['position']
        );
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->propertyGroupId, $this->propertyGroupOptionId);
    }
}
