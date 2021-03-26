<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class DeleteCategory extends AbstractAction
{
    private const URI = '/api/v2/category/%s';

    private string $categoryId;

    public function __construct(string $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_DELETE,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function getUri(): string
    {
        return sprintf(self::URI, $this->categoryId);
    }
}
