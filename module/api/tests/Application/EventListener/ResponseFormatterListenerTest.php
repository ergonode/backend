<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\ResponseFormatterListener;
use Ergonode\Api\Application\Response\AbstractResponse;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ResponseFormatterListenerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public function testInvokeSuccess(): void
    {
        $response = $this->createMock(AbstractResponse::class);
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
        $listener = new ResponseFormatterListener($this->serializer);

        $event = new ResponseEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $listener($event);
    }

    public function testInvokeWithWrongResponse(): void
    {
        $response = $this->createMock(JsonResponse::class);
        $response->expects($this->never())->method('getContent');
        $listener = new ResponseFormatterListener($this->serializer);

        $event = new ResponseEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $listener($event);
    }

    public function testInvokeWithWrongContent(): void
    {
        $response = $this->createMock(AbstractResponse::class);
        $response->expects($this->once())->method('getContent')->willReturn('test');
        $this->serializer->expects($this->never())->method('serialize');
        $listener = new ResponseFormatterListener($this->serializer);

        $event = new ResponseEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $listener($event);
    }
}
