<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostPropertyGroupAction extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/property-group';

    /**
     * @var Shopware6PropertyGroup
     */
    private Shopware6PropertyGroup $propertyGroup;

    /**
     * @param Shopware6PropertyGroup $propertyGroup
     */
    public function __construct(Shopware6PropertyGroup $propertyGroup)
    {
        $this->propertyGroup = $propertyGroup;
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
        return null;
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
        return self::URI;
    }
}
