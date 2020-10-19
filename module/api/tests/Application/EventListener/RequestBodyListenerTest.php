<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\RequestBodyListener;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 */
class RequestBodyListenerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var GetResponseEvent|MockObject
     */
    private $event;

    /**
     * @var Request|MockObject
     */
    private $request;

    /**
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->event = $this->createMock(GetResponseEvent::class);
        $this->request = $this->createMock(Request::class);
    }

    /**
     * @param string $contentType
     * @param string $method
     * @param string $content
     * @param array  $deserialize
     * @param string $expected
     *
     * @dataProvider dataProviderHappy
     */
    public function testInvokeHappy(
        string $contentType,
        string $method,
        string $content,
        array $deserialize,
        string $expected
    ): void {
        $this->event->expects(self::once())->method('getRequest')->willReturn($this->request);
        $this->request->expects(self::once())->method('getContentType')->willReturn($contentType);
        $this->request->expects(self::once())->method('getMethod')->willReturn($method);
        $this->request->expects(self::once())->method('getContent')->willReturn($content);
        $this->serializer->expects(self::once())->method('deserialize')->willReturn($deserialize);

        $listener = new RequestBodyListener($this->serializer);
        $listener($this->event);
        self::assertInstanceOf($expected, $this->request->request);
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
                'deserialize' => [
                    "collection" => [
                        "code" => "EN",
                        "active" => true,
                    ],
                ],
                'expected' => ParameterBag::class,
            ],
        ];
    }

    /**
     * @param string $contentType
     * @param string $method
     * @param string $content
     *
     * @dataProvider dataProviderUnhappy
     */
    public function testInvokeUnhappy(
        ?string $contentType,
        ?string $method,
        ?string $content
    ): void {
        $this->event->expects(self::once())->method('getRequest')->willReturn($this->request);
        $this->request->expects(self::once())->method('getContentType')->willReturn($contentType);
        $this->request->expects(self::once())->method('getMethod')->willReturn($method);
        $this->request->expects(self::once())->method('getContent')->willReturn($content);
        $this->serializer->expects(self::never())->method('deserialize');

        $listener = new RequestBodyListener($this->serializer);
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
