<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Product;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\GetProductList;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetProductListTest extends TestCase
{
    /**
     */
    public function testAction()
    {
        $action = new GetProductList();
        $request = $action->getRequest();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('', $request->getHeaderLine('Accept'));
        $this->assertSame('', $request->getHeaderLine('Cache-Control'));
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
        $this->assertSame(HttpRequest::METHOD_GET, $request->getMethod());
        $this->assertInstanceOf(Uri::class, $request->getUri());
    }
}
