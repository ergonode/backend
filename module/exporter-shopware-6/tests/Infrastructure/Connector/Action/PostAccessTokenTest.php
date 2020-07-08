<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PostAccessToken;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class PostAccessTokenTest extends TestCase
{
    /**
     */
    public function testAction()
    {
        $profile = $this->createMock(Shopware6ExportApiProfile::class);
        $action = new PostAccessToken($profile);
        $request = $action->getRequest();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('', $request->getHeaderLine('Accept'));
        $this->assertSame('', $request->getHeaderLine('Cache-Control'));
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
        $this->assertSame(HttpRequest::METHOD_POST, $request->getMethod());
        $this->assertInstanceOf(Uri::class, $request->getUri());
    }
}
