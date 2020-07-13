<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetCurrencyList extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/currency?%s';

    /**
     * @var int
     */
    private int $limit;

    /**
     * @param int $limit
     */
    public function __construct(int $limit = 500)
    {
        $this->limit = $limit;
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
     * @return array
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true);

        foreach ($data['data'] as $row) {
            $result[$row['id']] = [
                'id' => $row['id'],
                'iso' => $row['attributes']['isoCode'],
            ];
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        $query = [
            'limit' => $this->limit,
        ];

        return rtrim(sprintf(self::URI, http_build_query($query)), '?');
    }
}
