<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\EventListener;

use Ergonode\Api\Application\EventListener\ResponseSubscriber;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AbstractId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class ResponseSubscriberTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $mockSerializer;
    private ResponseSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->mockSerializer = $this->createMock(SerializerInterface::class);
        $this->subscriber = new ResponseSubscriber(
            $this->mockSerializer,
        );
    }

    /**
     * @dataProvider eventProvider
     */
    public function testOnViewEvent(ViewEvent $event, Response $expectedResponse): void
    {
        $this->mockSerializer->method('serialize')->willReturnCallback(fn ($data) => json_encode($data));

        $this->subscriber->onViewEvent($event);

        $this->assertEquals($expectedResponse->getContent(), $event->getResponse()->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $event->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $event->getResponse()->headers->get('Content-Type'));
    }

    public function eventProvider(): array
    {
        $kernel = $this->createMock(KernelInterface::class);
        $id = $this->createMock(AbstractId::class);

        return [
            [
                new ViewEvent(
                    $kernel,
                    Request::create('', Request::METHOD_POST),
                    KernelInterface::MASTER_REQUEST,
                    $id,
                ),
                new Response('{"id":""}', Response::HTTP_CREATED),
            ],
            [
                new ViewEvent(
                    $kernel,
                    Request::create('', Request::METHOD_GET),
                    KernelInterface::MASTER_REQUEST,
                    $id,
                ),
                new Response('{"id":""}', Response::HTTP_OK),
            ],
            [
                new ViewEvent(
                    $kernel,
                    Request::create('', Request::METHOD_POST),
                    KernelInterface::SUB_REQUEST,
                    $id,
                ),
                new Response('{"id":""}', Response::HTTP_OK),
            ],
            [
                new ViewEvent(
                    $kernel,
                    new Request(),
                    KernelInterface::MASTER_REQUEST,
                    null,
                ),
                new Response('null', Response::HTTP_NO_CONTENT),
            ],
            [
                new ViewEvent(
                    $kernel,
                    new Request(),
                    KernelInterface::MASTER_REQUEST,
                    ['data'],
                ),
                new Response('["data"]', Response::HTTP_OK),
            ],
        ];
    }
}
