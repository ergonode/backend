<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCustomFieldList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field?%s';

    /**
     * @var Shopware6QueryBuilder
     */
    private Shopware6QueryBuilder $query;

    /**
     * @param Shopware6QueryBuilder $query
     */
    public function __construct(Shopware6QueryBuilder $query)
    {
        $this->query = $query;
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
     * @return Shopware6CustomField[]
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (count($data['data']) > 0) {
            foreach ($data['data'] as $row) {
                $config = $row['attributes']['config'] ?: null;
                $result[] = new Shopware6CustomField(
                    $row['id'],
                    $row['attributes']['name'],
                    $row['attributes']['type'],
                    $config,
                    $row['attributes']['customFieldSetId']
                );
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->query->getQuery()), '?');
    }
}
