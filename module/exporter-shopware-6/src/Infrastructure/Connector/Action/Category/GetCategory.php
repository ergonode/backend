<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetCategory extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/category/%s';

    /**
     * @var string
     */
    private string $categoryId;

    /**
     * @param string $categoryId
     */
    public function __construct(string $categoryId)
    {
        $this->categoryId = $categoryId;
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
     * @return Shopware6Category
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content):Shopware6Category
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6Category(
            $data['data']['id'],
            $data['data']['attributes']['name'],
            $data['data']['attributes']['parentId'],
            $data['data']['attributes']['active'],
            $data['data']['attributes']['visible']
        );
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI, $this->categoryId);
    }
}
