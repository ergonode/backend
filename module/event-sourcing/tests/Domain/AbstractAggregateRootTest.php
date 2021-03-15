<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Tests\Domain;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

class AbstractAggregateRootTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreate(): void
    {
        $aggregate = $this->getClass();
        $this->assertSame(0, $aggregate->getSequence());
    }

    /**
     * @throws \Exception
     */
    public function testApply(): void
    {
        $event = $this->createMock(AggregateEventInterface::class);
        $aggregate = $this->getClass();
        $aggregate->apply($event);
        $events = $aggregate->popEvents();
        $result = reset($events);
        $this->assertSame($event, $result[0]->getEvent());
        $this->assertSame(1, $aggregate->getSequence());
    }

    /**
     * @throws \Exception
     */
    public function testInitialize(): void
    {
        $event = $this->createMock(AggregateEventInterface::class);
        $envelope = $this->createMock(DomainEventEnvelope::class);
        $envelope->method('getEvent')->willReturn($event);
        $stream = new DomainEventStream([$envelope]);
        $aggregate = $this->getClass();
        $aggregate->initialize($stream);
        $this->assertSame(1, $aggregate->getSequence());
    }

    private function getClass(): AbstractAggregateRoot
    {
        $id = $this->createMock(AggregateId::class);

        return new class($id) extends AbstractAggregateRoot {

            public function __construct(AggregateId $id)
            {
                $this->id = $id;
            }


            public function getId(): AggregateId
            {
                return $this->id;
            }
        };
    }
}
