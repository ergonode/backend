<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\ConfiguratorSettings;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetConfiguratorSettings extends AbstractAction
{
    private const URI = '/api/v2/product/%s/configurator-settings';

    private string $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
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
     * @return Shopware6ProductConfiguratorSettings[]|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?array
    {
        $result = null;
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (count($data['data']) > 0) {
            foreach ($data['data'] as $row) {
                $result[] = new Shopware6ProductConfiguratorSettings(
                    $row['id'],
                    $row['attributes']['optionId']
                );
            }
        }

        return $result;
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->productId);
    }
}
