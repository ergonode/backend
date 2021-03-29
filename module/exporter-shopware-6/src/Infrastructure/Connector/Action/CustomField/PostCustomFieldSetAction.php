<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSetConfig;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCustomFieldSetAction extends AbstractAction
{
    private const URI = '/api/v2/custom-field-set?%s';

    private AbstractShopware6CustomFieldSet $customFieldSet;

    private bool $response;

    public function __construct(AbstractShopware6CustomFieldSet $customFieldSet, bool $response = false)
    {
        $this->customFieldSet = $customFieldSet;
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
        return json_encode($this->customFieldSet->jsonSerialize(), JSON_THROW_ON_ERROR);
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
