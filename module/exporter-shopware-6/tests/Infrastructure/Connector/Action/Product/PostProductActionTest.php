<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PostProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostProductActionTest extends TestCase
{
    public function testAction(): void
    {
        $product = new Shopware6Product('SKU');
        $action = new PostProductAction($product);
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
