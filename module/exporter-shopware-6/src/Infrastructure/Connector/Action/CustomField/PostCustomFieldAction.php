<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCustomFieldAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field?%s';

    private AbstractShopware6CustomField $customField;

    private bool $response;

    public function __construct(AbstractShopware6CustomField $customField, bool $response = false)
    {
        $this->customField = $customField;
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
    public function parseContent(?string $content): ?AbstractShopware6CustomField
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $config = new Shopware6CustomFieldConfig(
            $data['data']['attributes']['config']['type'] ?? null,
            $data['data']['attributes']['config']['customFieldType'] ?? null,
            $data['data']['attributes']['config']['label'] ?? null,
            $data['data']['attributes']['config']['componentName'] ?? null,
            $data['data']['attributes']['config']['dateType'] ?? null,
            $data['data']['attributes']['config']['numberType'] ?? null,
            $data['data']['attributes']['config']['options'] ?? null
        );

        return new Shopware6CustomField(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['type'],
            $config,
            $data['data']['attributes']['customFieldSetId']
        );
    }

    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->customField, 'json');
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
