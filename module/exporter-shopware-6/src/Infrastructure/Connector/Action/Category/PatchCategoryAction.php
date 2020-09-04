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
class PatchCategoryAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/category/';

    /**
     * @var Shopware6Category
     */
    private Shopware6Category $category;

    /**
     * @param Shopware6Category $category
     */
    public function __construct(Shopware6Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_PATCH,
            $this->getUri(),
            $this->buildHeaders(),
            $this->buildBody()
        );
    }

    /**
     * @param string|null $content
     *
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
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
        return self::URI.$this->category->getId();
    }
}
