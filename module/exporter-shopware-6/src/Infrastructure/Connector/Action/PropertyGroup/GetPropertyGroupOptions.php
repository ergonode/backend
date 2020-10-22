<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetPropertyGroupOptions extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/property-group/%s/options/%s';

    /**
     * @var string
     */
    private string $propertyGroupId;

    /**
     * @var string
     */
    private string $propertyGroupOptionId;

    /**
     * @param string $propertyGroupId
     * @param string $propertyGroupOptionId
     */
    public function __construct(string $propertyGroupId, string $propertyGroupOptionId)
    {
        $this->propertyGroupId = $propertyGroupId;
        $this->propertyGroupOptionId = $propertyGroupOptionId;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @param string|null $content
     *
     * @return Shopware6PropertyGroupOption
     *
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

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI, $this->propertyGroupId, $this->propertyGroupOptionId);
    }
}
