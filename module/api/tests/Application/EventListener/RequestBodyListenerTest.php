<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\RequestBodyListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestBodyListenerTest extends TestCase
{
    private GetResponseEvent $event;

    private Request $request;

    protected function setUp(): void
    {
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->request = $this->createMock(Request::class);
    }

    /**
     * @dataProvider dataProviderHappy
     */
    public function testInvokeHappy(
        string $contentType,
        string $method,
        string $content,
        string $expected
    ): void {
        $this->event->expects($this->once())->method('getRequest')->willReturn($this->request);
        $this->request->expects($this->once())->method('getContentType')->willReturn($contentType);
        $this->request->expects($this->once())->method('getMethod')->willReturn($method);
        $this->request->expects($this->once())->method('getContent')->willReturn($content);

        $listener = new RequestBodyListener();
        $listener($this->event);
        $this->assertInstanceOf($expected, $this->request->request);
    }

    /**
     * @return array
     */
    public function dataProviderHappy(): array
    {
        return [
            [
                'contentType' => 'json',
                'method' => 'PUT',
                'content' => '{
  "collection": [
    {
      "code": "EN",
      "active": true
    }
  ]
}',
                'expected' => ParameterBag::class,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderUnhappy
     */
    public function testInvokeUnhappy(
        ?string $contentType,
        ?string $method,
        ?string $content
    ): void {
        $this->event->expects($this->once())->method('getRequest')->willReturn($this->request);
        $this->request->expects($this->once())->method('getContentType')->willReturn($contentType);
        $this->request->expects($this->once())->method('getMethod')->willReturn($method);
        $this->request->expects($this->once())->method('getContent')->willReturn($content);

        $listener = new RequestBodyListener();
        $listener($this->event);
    }

    /**
     * @return array
     */
    public function dataProviderUnhappy(): array
    {
        return [
            [
                'contentType' => 'json',
                'method' => null,
                'content' => '{
  "collection": [
    {
      "code": "EN",
      "active": true
    }
  ]
}',
            ],
            [
                'contentType' => 'json',
                'method' => 'PUT',
                'content' => null,
            ],
            [
                'contentType' => 'json',
                'method' => 'test',
                'content' => '{
  "collection": [
    {
      "code": "EN",
      "active": true
    }
  ]
}',
            ],
            [
                'contentType' => 'test',
                'method' => 'PUT',
                'content' => '{
  "collection": [
    {
      "code": "EN",
      "active": true
    }
  ]
}',
            ],
        ];
    }
}
