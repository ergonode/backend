<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\ExceptionListener;
use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Application\Response\ExceptionResponse;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ExceptionListenerTest extends TestCase
{
    /**
     * @var ExceptionMapperInterface|MockObject
     */
    private $exceptionMapper;

    /**
     * @var HandlerFailedException|MockObject
     */
    private $handlerFailedException;

    /**
     * @var AuthenticationCredentialsNotFoundException|MockObject
     */
    private $authenticationCredentialNotFoundException;

    protected function setUp(): void
    {
        $this->exceptionMapper = $this->createMock(ExceptionMapperInterface::class);
        $this->authenticationCredentialNotFoundException =
            $this->createMock(AuthenticationCredentialsNotFoundException::class);
        $this->handlerFailedException = $this->createMock(HandlerFailedException::class);
    }

    public function testInvokeWithoutMappingWithoutSerializer(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->authenticationCredentialNotFoundException
        );

        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($event);

        self::assertEquals(500, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(ExceptionResponse::class, $event->getResponse());
    }

    public function testInvokeWithoutMappingWithHandlerFailedExceptionWithoutSerializer(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);
        $this
            ->handlerFailedException
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->handlerFailedException
        );

        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($event);

        self::assertEquals(500, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(ExceptionResponse::class, $event->getResponse());
    }

    public function testInvokeWithMappingWithHandlerFailedExceptionWithoutSerializer(): void
    {
        $this->exceptionMapper->method('map')->willReturn([
            'http' => [
                'code' => 403,
            ],
            'content' => [
                'code' => 403,
                'message' => 'test message',
            ],
        ]);

        $this
            ->handlerFailedException
            ->expects($this->once())
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->handlerFailedException
        );

        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($event);

        self::assertEquals(403, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(ExceptionResponse::class, $event->getResponse());
    }

    public function testInvokeWithMappingWithoutSerializer(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn([
            'http' => [
                'code' => 403,
            ],
            'content' => [
                'code' => 403,
                'message' => 'test message',
            ],
        ]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->authenticationCredentialNotFoundException
        );

        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($event);

        self::assertEquals(403, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(ExceptionResponse::class, $event->getResponse());
    }

    public function testInvokeWithoutMapping(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->authenticationCredentialNotFoundException
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $listener = new ExceptionListener($this->exceptionMapper, $serializer);
        $listener($event);

        self::assertEquals(500, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(Response::class, $event->getResponse());
        self::assertEquals('application/json', $event->getResponse()->headers->get('Content-Type'));
    }

    public function testInvokeWithoutMappingWithHandlerFailedException(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);

        $this
            ->handlerFailedException
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->handlerFailedException
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $listener = new ExceptionListener($this->exceptionMapper, $serializer);
        $listener($event);

        self::assertEquals(500, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(Response::class, $event->getResponse());
        self::assertEquals('application/json', $event->getResponse()->headers->get('Content-Type'));
    }

    public function testInvokeWithMappingWithHandlerFailedException(): void
    {
        $this->exceptionMapper->method('map')->willReturn([
            'http' => [
                'code' => 403,
            ],
            'content' => [
                'code' => 403,
                'message' => 'test message',
            ],
        ]);

        $this
            ->handlerFailedException
            ->expects($this->once())
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->handlerFailedException
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $listener = new ExceptionListener($this->exceptionMapper, $serializer);
        $listener($event);

        self::assertEquals(403, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(Response::class, $event->getResponse());
        self::assertEquals('application/json', $event->getResponse()->headers->get('Content-Type'));
    }

    public function testInvokeWithMapping(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn([
            'http' => [
                'code' => 403,
            ],
            'content' => [
                'code' => 403,
                'message' => 'test message',
            ],
        ]);

        $event = new ExceptionEvent(
            $this->createMock(KernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->authenticationCredentialNotFoundException
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $listener = new ExceptionListener($this->exceptionMapper, $serializer);
        $listener($event);

        self::assertEquals(403, $event->getResponse()->getStatusCode());
        self::assertInstanceOf(Response::class, $event->getResponse());
        self::assertEquals('application/json', $event->getResponse()->headers->get('Content-Type'));
    }
}
