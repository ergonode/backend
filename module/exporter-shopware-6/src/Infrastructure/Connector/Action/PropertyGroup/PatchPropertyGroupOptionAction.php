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
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PatchPropertyGroupOptionAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/property-group/%s/options/%s';

    /**
     * @var string
     */
    private string $propertyGroupId;

    /**
     * @var Shopware6PropertyGroupOption
     */
    private Shopware6PropertyGroupOption $propertyGroupOption;

    /**
     * @param string                       $propertyGroupId
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     */
    public function __construct(string $propertyGroupId, Shopware6PropertyGroupOption $propertyGroupOption)
    {
        $this->propertyGroupId = $propertyGroupId;
        $this->propertyGroupOption = $propertyGroupOption;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_PATCH,
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

        return $serializer->serialize($this->propertyGroupOption, 'json');
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI, $this->propertyGroupId, $this->propertyGroupOption->getId());
    }
}
