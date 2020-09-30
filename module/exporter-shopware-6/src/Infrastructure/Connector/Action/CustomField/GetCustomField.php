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
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetCustomField extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field/%s';

    /**
     * @var string
     */
    private string $customFieldId;

    /**
     * @param string $customFieldId
     */
    public function __construct(string $customFieldId)
    {
        $this->customFieldId = $customFieldId;
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
     * @return Shopware6CustomField|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?Shopware6CustomField
    {
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
    private function getUri(): string
    {
        return sprintf(self::URI, $this->customFieldId);
    }
}
