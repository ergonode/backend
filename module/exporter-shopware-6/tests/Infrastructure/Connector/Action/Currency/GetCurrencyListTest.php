<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\Currency;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\GetCurrencyList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetCurrencyListTest extends TestCase
{
    public function testAction(): void
    {
        $action = new GetCurrencyList(new Shopware6QueryBuilder());
        $request = $action->getRequest();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('', $request->getHeaderLine('Accept'));
        self::assertSame('', $request->getHeaderLine('Cache-Control'));
        self::assertSame('', $request->getHeaderLine('Content-Type'));
        self::assertSame(HttpRequest::METHOD_GET, $request->getMethod());
        self::assertInstanceOf(Uri::class, $request->getUri());
    }
}
