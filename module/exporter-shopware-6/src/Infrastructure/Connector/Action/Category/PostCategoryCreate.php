<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostCategoryCreate extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/category';

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $parent;

    /**
     * @param string      $name
     * @param string|null $parent
     */
    public function __construct(string $name, ?string $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
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
        $body = [
            'visible' => true,
            'active' => true,
            'name' => $this->name,
            '_uniqueIdentifier' => $this->name,
        ];
        if ($this->parent) {
            $body['parentId'] = $this->parent;
        }

        return json_encode($body);
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return self::URI;
    }
}
