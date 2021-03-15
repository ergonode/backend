<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PostAccessToken;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class PostAccessTokenTest extends TestCase
{
    public function testAction(): void
    {
        $channel = $this->createMock(Shopware6Channel::class);
        $action = new PostAccessToken($channel);
        $request = $action->getRequest();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('', $request->getHeaderLine('Accept'));
        self::assertSame('', $request->getHeaderLine('Cache-Control'));
        self::assertSame('', $request->getHeaderLine('Content-Type'));
        self::assertSame(HttpRequest::METHOD_POST, $request->getMethod());
        self::assertInstanceOf(Uri::class, $request->getUri());
    }
}
