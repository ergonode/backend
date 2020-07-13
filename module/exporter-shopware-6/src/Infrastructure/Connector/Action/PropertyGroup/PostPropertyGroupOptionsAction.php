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
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostPropertyGroupOptionsAction extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/property-group/%s/options';

    /**
     * @var string
     */
    private string $propertyGroupId;

    /**
     * @var string
     */
    private string $optionName;

    /**
     * @param string $propertyGroupId
     * @param string $optionName
     */
    public function __construct(string $propertyGroupId, string $optionName)
    {
        $this->propertyGroupId = $propertyGroupId;
        $this->optionName = $optionName;
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
     * @return array|object|string|null
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
        $body = [
            'name' => $this->optionName,
        ];

        return json_encode($body);
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI, $this->propertyGroupId);
    }
}
