<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Tax;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\PostTaxCreate;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Tax;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostTaxCreateTest extends TestCase
{
    public function testAction(): void
    {
        $tax = new Shopware6Tax(
            null,
            23,
            '23%'
        );
        $action = new PostTaxCreate($tax);
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
