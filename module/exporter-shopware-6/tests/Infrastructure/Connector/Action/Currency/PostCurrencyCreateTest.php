<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Currency;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\PostCurrencyCreate;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostCurrencyCreateTest extends TestCase
{
    /**
     */
    public function testAction()
    {
        $action = new PostCurrencyCreate('RUB');
        $request = $action->getRequest();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('', $request->getHeaderLine('Accept'));
        $this->assertSame('', $request->getHeaderLine('Cache-Control'));
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
        $this->assertSame(HttpRequest::METHOD_POST, $request->getMethod());
        $this->assertInstanceOf(Uri::class, $request->getUri());
        $this->assertNull($action->parseContent(null));
    }
}
