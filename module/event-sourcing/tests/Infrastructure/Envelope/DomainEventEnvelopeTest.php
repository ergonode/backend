<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Tests\Infrastructure\Envelope;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class DomainEventEnvelopeTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(AggregateId::class);
        $sequence = 0;
        $event = $this->createMock(AggregateEventInterface::class);
        $recordedAt = $this->createMock(\DateTime::class);
        $envelope = new DomainEventEnvelope($id, $sequence, $event, $recordedAt);
        $this->assertSame($id, $envelope->getAggregateId());
        $this->assertSame($event, $envelope->getEvent());
        $this->assertSame($sequence, $envelope->getSequence());
        $this->assertSame($recordedAt, $envelope->getRecordedAt());
        $this->assertSame(get_class($event), $envelope->getType());
    }
}
