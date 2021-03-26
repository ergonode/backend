<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCategoryList extends AbstractAction
{
    private const URI = '/api/v2/category?%s';

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
     * @return Shopware6Category[]
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        foreach ($data['data'] as $row) {
            $result[] = new Shopware6Category(
                $row['id'],
                $row['attributes']['name'],
                $row['attributes']['parentId'],
                $row['attributes']['active'],
                $row['attributes']['visible']
            );
        }

        return $result;
    }

    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->query->getQuery()), '?');
    }
}
