<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Tests\Infrastructure\Envelope;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
class DomainEventEnvelopeTest extends TestCase
{
    /**
     */
    public function testCreation(): void
    {
        $id = $this->createMock(AggregateId::class);
        $sequence = 0;
        $event = $this->createMock(DomainEventInterface::class);
        $recordedAt = $this->createMock(\DateTime::class);
        $envelope = new DomainEventEnvelope($id, $sequence, $event, $recordedAt);
        self::assertSame($id, $envelope->getAggregateId());
        self::assertSame($event, $envelope->getEvent());
        self::assertSame($sequence, $envelope->getSequence());
        self::assertSame($recordedAt, $envelope->getRecordedAt());
        self::assertSame(get_class($event), $envelope->getType());
    }
}
