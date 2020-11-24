<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSetConfig;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCustomFieldSetAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field-set?%s';

    private AbstractShopware6CustomFieldSet $customField;

    private bool $response;

    public function __construct(AbstractShopware6CustomFieldSet $customField, bool $response = false)
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
    public function parseContent(?string $content): ?AbstractShopware6CustomFieldSet
    {
        if (null === $content) {
            return null;
        }

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $config = new Shopware6CustomFieldSetConfig(
            $data['data']['attributes']['config']['translated'],
            $data['data']['attributes']['config']['label'] ?: null
        );

        return new Shopware6CustomFieldSet(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $config
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
