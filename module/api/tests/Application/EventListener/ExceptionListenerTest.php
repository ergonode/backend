<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\ExceptionListener;
use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Application\Response\ExceptionResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 */
class ExceptionListenerTest extends TestCase
{
    /**
     * @var ExceptionMapperInterface|MockObject
     */
    private $exceptionMapper;

    /**
     * @var ExceptionEvent|MockObject
     */
    private $event;

    /**
     * @var HandlerFailedException|MockObject
     */
    private $handlerFailedException;

    /**
     * @var AuthenticationCredentialsNotFoundException|MockObject
     */
    private $authenticationCredentialNotFoundException;

    /**
     */
    protected function setUp(): void
    {
        $this->exceptionMapper = $this->createMock(ExceptionMapperInterface::class);
        $this->event = $this->createMock(ExceptionEvent::class);
        $this->authenticationCredentialNotFoundException =
            $this->createMock(AuthenticationCredentialsNotFoundException::class);
        $this->handlerFailedException = $this->createMock(HandlerFailedException::class);
    }

    /**
     */
    public function testInvokeWithoutMapping(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);
        $this
            ->event
            ->expects($this->once())
            ->method('getThrowable')->willReturn($this->authenticationCredentialNotFoundException);
        $this->event
            ->expects($this->once())
            ->method('setResponse')->willreturnCallback(function ($response) {
                $this->assertInstanceOf(ExceptionResponse::class, $response);
                $this->assertEquals(500, $response->getStatusCode());
            });
        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($this->event);
    }

    /**
     */
    public function testInvokeWithoutMappingWithHandlerFailedException(): void
    {
        $this->exceptionMapper->expects($this->once())->method('map')->willReturn(null);
        $this
            ->event
            ->expects($this->once())->method('getThrowable')->willReturn($this->handlerFailedException);
        $this
            ->handlerFailedException
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);
        $this->event
            ->expects($this->once())
            ->method('setResponse')->willreturnCallback(function ($response) {
                $this->assertInstanceOf(ExceptionResponse::class, $response);
                $this->assertEquals(500, $response->getStatusCode());
            });
        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($this->event);
    }

    /**
     */
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
            ->event
            ->expects($this->once())->method('getThrowable')->willReturn($this->handlerFailedException);
        $this
            ->handlerFailedException
            ->expects($this->once())
            ->method('getNestedExceptions')->willReturn([$this->authenticationCredentialNotFoundException]);
        $this->event
            ->expects($this->once())
            ->method('setResponse')->willreturnCallback(function ($response) {
                $this->assertInstanceOf(ExceptionResponse::class, $response);
                $this->assertEquals(403, $response->getStatusCode());
            });
        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($this->event);
    }

    /**
     */
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
        $this
            ->event
            ->expects($this->once())
            ->method('getThrowable')->willReturn($this->authenticationCredentialNotFoundException);
        $this->event
            ->expects($this->once())
            ->method('setResponse')->willreturnCallback(function ($response) {
                $this->assertInstanceOf(ExceptionResponse::class, $response);
                $this->assertEquals(403, $response->getStatusCode());
            });
        $listener = new ExceptionListener($this->exceptionMapper);
        $listener($this->event);
    }
}
