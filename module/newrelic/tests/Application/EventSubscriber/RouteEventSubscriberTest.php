<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Tests\Application\EventSubscriber;

use Ergonode\NewRelic\Application\EventSubscriber\RouteEventSubscriber;
use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RouteEventSubscriberTest extends TestCase
{
    /**
     * @var NewRelicInterface|MockObject
     */
    private $mockNewRelic;
    private RouteEventSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->mockNewRelic = $this->createMock(NewRelicInterface::class);

        $this->subscriber = new RouteEventSubscriber(
            $this->mockNewRelic,
        );
    }

    public function testShouldSetTransactionName(): void
    {
        $this->mockNewRelic->expects($this->once())->method('nameTransaction');

        $this->subscriber->onKernelRequest(
            new RequestEvent(
                $this->createMock(HttpKernelInterface::class),
                new Request([], [], ['_route' => 'route_name']),
                HttpKernelInterface::MASTER_REQUEST,
            ),
        );
    }

    public function testShouldNotSetTransactionNameForNonMasterRequest(): void
    {
        $this->mockNewRelic->expects($this->never())->method('nameTransaction');

        $this->subscriber->onKernelRequest(
            new RequestEvent(
                $this->createMock(HttpKernelInterface::class),
                new Request([], [], ['_route' => 'route_name']),
                HttpKernelInterface::SUB_REQUEST,
            ),
        );
    }

    public function testShouldNotSetTransactionNameForMissingRouteName(): void
    {
        $this->mockNewRelic->expects($this->never())->method('nameTransaction');

        $this->subscriber->onKernelRequest(
            new RequestEvent(
                $this->createMock(HttpKernelInterface::class),
                new Request(),
                HttpKernelInterface::SUB_REQUEST,
            ),
        );
    }
}
