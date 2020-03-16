<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\ResponseFormatterListener;
use Ergonode\Api\Application\Response\AbstractResponse;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 */
class ResponseFormatterListenerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;
    /**
     * @var ResponseEvent|MockObject
     */
    private $event;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->event = $this->createMock(ResponseEvent::class);
    }

    /**
     *
     */
    public function testInvokeSuccess(): void
    {
        $response = $this->createMock(AbstractResponse::class);
        $this->event->expects($this->once())->method('getResponse')->willReturn($response);
        $content = $this->createMock(AuthenticationCredentialsNotFoundException::class);
        $response->expects($this->once())->method('getContent')->willReturn($content);
        $this->serializer->expects($this->once())->method('serialize')->willReturn(
            '{
    "code": "401",
    "message": "A Token was not found in the TokenStorage.",
    "trace": [#random/path/to/something"]
    }'
        );
        $response->expects($this->once())->method('setContent');
        $this->event->expects($this->once())->method('setResponse');
        $listener = new ResponseFormatterListener($this->serializer);
        $listener($this->event);
    }

    /**
     */
    public function testInvokeWithWrongResponse(): void
    {
        $response = $this->createMock(JsonResponse::class);
        $this->event->expects($this->once())->method('getResponse')->willReturn($response);
        $response->expects($this->never())->method('getContent');
        $listener = new ResponseFormatterListener($this->serializer);
        $listener($this->event);
    }

    /**
     */
    public function testInvokeWithWrongContent(): void
    {
        $response = $this->createMock(AbstractResponse::class);
        $this->event->expects($this->once())->method('getResponse')->willReturn($response);
        $response->expects($this->once())->method('getContent')->willReturn('test');
        $this->serializer->expects($this->never())->method('serialize');
        $listener = new ResponseFormatterListener($this->serializer);
        $listener($this->event);
    }
}
