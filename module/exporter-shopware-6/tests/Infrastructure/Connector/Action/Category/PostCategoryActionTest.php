<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Category;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCategoryActionTest extends TestCase
{
    /**
     * @var Shopware6Category|MockObject
     */
    private Shopware6Category $category;

    protected function setUp(): void
    {
        $this->category = new Shopware6Category(
            'any_id',
            'category_name',
            null
        );
    }

    public function testAction(): void
    {
        $action = new PostCategoryAction($this->category);
        $request = $action->getRequest();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('', $request->getHeaderLine('Accept'));
        self::assertSame('', $request->getHeaderLine('Cache-Control'));
        self::assertSame('', $request->getHeaderLine('Content-Type'));
        self::assertSame(HttpRequest::METHOD_POST, $request->getMethod());
        self::assertInstanceOf(Uri::class, $request->getUri());
        self::assertNull($action->parseContent(null));
    }
}
