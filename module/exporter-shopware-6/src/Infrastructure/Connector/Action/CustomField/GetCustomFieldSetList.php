<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSetConfig;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCustomFieldSetList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field-set?%s';

    private Shopware6QueryBuilder $query;

    public function __construct(Shopware6QueryBuilder $query)
    {
        $this->query = $query;
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
     * @return AbstractShopware6CustomFieldSet[]
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['data'] as $row) {
            $config = new Shopware6CustomFieldSetConfig(
                $row['attributes']['config']['translated'],
                $row['attributes']['config']['label'] ?: null
            );
            $result[] = new Shopware6CustomFieldSet(
                $row['id'],
                $row['attributes']['name'],
                $config
            );
        }

        return $result;
    }

    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->query->getQuery()), '?');
    }
}
