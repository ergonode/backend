<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostPropertyGroupAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/property-group?%s';

    /**
     * @var Shopware6PropertyGroup
     */
    private Shopware6PropertyGroup $propertyGroup;

    /**
     * @var bool
     */
    private bool $response;

    /**
     * @param Shopware6PropertyGroup $propertyGroup
     * @param bool                   $response
     */
    public function __construct(Shopware6PropertyGroup $propertyGroup, bool $response = false)
    {
        $this->propertyGroup = $propertyGroup;
        $this->response = $response;
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
     * @param string|null $content
     *
     * @return null
     */
    public function parseContent(?string $content)
    {
        $result = [];
        $data = json_decode($content, true);

            $result = new Shopware6PropertyGroup(
                $data['data']['id'],
                $data['data']['attributes']['name'],
                $data['data']['attributes']['displayType'],
                $data['data']['attributes']['sortingType']
            );

        return $result;
    }

    /**
     * @return string
     */
    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->propertyGroup, 'json');
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
