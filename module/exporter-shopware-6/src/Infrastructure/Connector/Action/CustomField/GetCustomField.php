<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCustomField extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field/%s';

    private string $customFieldId;

    public function __construct(string $customFieldId)
    {
        $this->customFieldId = $customFieldId;
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
    public function parseContent(?string $content): ?Shopware6CustomField
    {
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

    private function getUri(): string
    {
        return sprintf(self::URI, $this->customFieldId);
    }
}
