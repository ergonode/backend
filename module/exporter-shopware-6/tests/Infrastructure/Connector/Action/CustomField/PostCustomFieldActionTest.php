<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PostCustomFieldAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCustomFieldActionTest extends TestCase
{
    private Shopware6CustomField $customField;

    protected function setUp(): void
    {
        $this->customField = new Shopware6CustomField(
            'any_id',
            'custom_field_name'
        );
    }

    public function testAction(): void
    {
        $action = new PostCustomFieldAction($this->customField);
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
