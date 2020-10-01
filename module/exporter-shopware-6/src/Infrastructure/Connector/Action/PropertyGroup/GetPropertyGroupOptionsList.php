<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetPropertyGroupOptionsList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/property-group/%s/options?%s';

    /**
     * @var string
     */
    private string $propertyGroupId;

    /**
     * @var array|array[]
     */
    private array $query;

    /**
     * @param string   $propertyGroupId
     * @param array    $query
     * @param int      $limit
     * @param int|null $page
     */
    public function __construct(string $propertyGroupId, array $query = [], int $limit = 500, int $page = null)
    {
        $this->propertyGroupId = $propertyGroupId;

        $this->query = [
            'query' => $query ? $query : [],
        ];
        if ($limit > 0) {
            $this->query['limit'] = $limit;
        }
        if ($page > 0) {
            $this->query['page'] = $page;
        }
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
     * @return Shopware6PropertyGroupOption[]
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true);

        foreach ($data['data'] as $datum) {
            $result[] = new Shopware6PropertyGroupOption(
                $datum['id'],
                $datum['attributes']['name'],
                $datum['attributes']['mediaId'],
                $datum['attributes']['position']
            );
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, $this->propertyGroupId, http_build_query($this->query)), '?');
    }
}
