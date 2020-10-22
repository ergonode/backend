<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCustomFieldAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field?%s';

    /**
     * @var Shopware6CustomField
     */
    private Shopware6CustomField $customField;

    /**
     * @var bool
     */
    private bool $response;

    /**
     * @param Shopware6CustomField $customField
     * @param bool                 $response
     */
    public function __construct(Shopware6CustomField $customField, bool $response = false)
    {
        $this->customField = $customField;
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
     * @return Shopware6CustomField|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?Shopware6CustomField
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $config = $data['data']['attributes']['config'] ?: null;

        return new Shopware6CustomField(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['type'],
            $config,
            $data['data']['attributes']['customFieldSetId']
        );
    }

    /**
     * @return string
     */
    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->customField, 'json');
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
