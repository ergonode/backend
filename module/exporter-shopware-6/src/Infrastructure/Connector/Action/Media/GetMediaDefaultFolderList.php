<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\SwagQLBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6MediaDefaultFolder;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetMediaDefaultFolderList extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/media-default-folder?%s';

    /**
     * @var SwagQLBuilder
     */
    private SwagQLBuilder $query;

    /**
     * @param SwagQLBuilder $query
     */
    public function __construct(SwagQLBuilder $query)
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
     * @return array
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true);

        foreach ($data['data'] as $row) {
            $result[] = new Shopware6MediaDefaultFolder(
                $row['id'],
                $row['attributes']['entity'],
                $row['relationships']['folder']['data']['id']
            );
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
