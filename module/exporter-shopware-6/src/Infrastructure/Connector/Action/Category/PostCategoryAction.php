<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostCategoryAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/category?%s';

    /**
     * @var Shopware6Category
     */
    private Shopware6Category $category;


    /**
     * @var bool
     */
    private bool $response;

    /**
     * @param Shopware6Category $category
     * @param bool              $response
     */
    public function __construct(Shopware6Category $category, bool $response = false)
    {
        $this->category = $category;
        $this->response = $response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_POST,
            $this->getUri(),
            $this->buildHeaders(),
            $this->buildBody()
        );
    }

    /**
     * @param string|null $content
     *
     * @return Shopware6Category|null
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): ?Shopware6Category
    {
        if (null === $content) {
            return null;
        }

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
    private function buildBody(): string
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->category, 'json');
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        $query = [];
        if ($this->response) {
            $query['_response'] = 'true';
        }

        return rtrim(sprintf(self::URI, http_build_query($query)), '?');
    }
}
