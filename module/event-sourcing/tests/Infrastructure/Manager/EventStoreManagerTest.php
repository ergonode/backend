<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Tests\Infrastructure\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Statement;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Manager\AggregateBuilderInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\EventSourcing\Infrastructure\Snapshot\AggregateSnapshotInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\EventSourcing\Infrastructure\DomainEventProjectorInterface;
use Ergonode\SharedKernel\Domain\Bus\DomainEventBusInterface;

class EventStoreManagerTest extends TestCase
{
    /**
     * @var AggregateBuilderInterface|MockObject
     */
    private $mockBuilder;
    /**
     * @var DomainEventStoreInterface|MockObject
     */
    private $mockEventStore;
    /**
     * @var DomainEventBusInterface|MockObject
     */
    private DomainEventBusInterface $mockEventBus;
    /**
     * @var AggregateSnapshotInterface|MockObject
     */
    private $mockSnapshot;
    /**
     * @var Connection|MockObject
     */
    private $mockConnection;
    /**
     * @var LoggerInterface|MockObject
     */
    private $mockLogger;
    private EventStoreManager $manager;

    private DomainEventProjectorInterface $projector;

    protected function setUp(): void
    {
        $this->mockBuilder = $this->createMock(AggregateBuilderInterface::class);
        $this->mockEventStore = $this->createMock(DomainEventStoreInterface::class);
        $this->mockEventBus = $this->createMock(DomainEventBusInterface::class);
        $this->mockSnapshot = $this->createMock(AggregateSnapshotInterface::class);
        $this->mockConnection = $this->createMock(Connection::class);
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->projector = $this->createMock(DomainEventProjectorInterface::class);


        $this->manager = new EventStoreManager(
            $this->mockBuilder,
            $this->mockEventStore,
            $this->mockEventBus,
            $this->mockSnapshot,
            $this->projector,
            $this->mockConnection,
            $this->mockLogger,
        );
    }

    public function testShouldLoad(): void
    {
        $stmt = $this->createMock(Statement::class);
        $stmt->method('fetch')->willReturn(self::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('expr')->willReturn($this->createMock(ExpressionBuilder::class));
        $queryBuilder->method('execute')->willReturn($stmt);
        $this->mockConnection->method('createQueryBuilder')->willReturn($queryBuilder);
        $aggregate = $this->createMock(AbstractAggregateRoot::class);
        $this->mockBuilder->method('build')->willReturn($aggregate);
        $eventStream = $this->createMock(DomainEventStream::class);
        $this->mockEventStore->method('load')->willReturn($eventStream);
        $aggregate->expects($this->once())->method('initialize');

        $result = $this->manager->load(new AggregateId((string) Uuid::uuid4()));

        $this->assertSame($aggregate, $result);
    }

    public function testShouldSave(): void
    {
        $aggregate = $this->createMock(AbstractAggregateRoot::class);
        $aggregate->method('getSequence')->willReturn(1);
        $eventStream = new DomainEventStream([
            new DomainEventEnvelope(
                new AggregateId((string) Uuid::uuid4()),
                1,
                $this->createMock(AggregateEventInterface::class),
                new \DateTime(),
            ),
        ]);
        $aggregate->method('popEvents')->willReturn($eventStream);
        $this->mockEventStore->method('append')->willReturn(1);
        $this->mockConnection->expects($this->once())->method('insert');
        $this->mockSnapshot->expects($this->once())->method('save');
        $this->mockEventBus->expects($this->once())->method('dispatch');
        $this->projector->expects($this->once())->method('project');
        $this->mockLogger->expects($this->never())->method('notice');

        $this->manager->save($aggregate);
    }

    public function testShouldSaveForConflictingSequence(): void
    {
        $aggregate = $this->createMock(AbstractAggregateRoot::class);
        $aggregate->method('getSequence')->willReturn(2);
        $eventStream = new DomainEventStream([
            new DomainEventEnvelope(
                new AggregateId((string) Uuid::uuid4()),
                2,
                $this->createMock(AggregateEventInterface::class),
                new \DateTime(),
            ),
        ]);
        $aggregate->method('popEvents')->willReturn($eventStream);
        $this->mockEventStore->method('append')->willReturn(3);
        $this->mockConnection->expects($this->never())->method('insert');
        $this->mockSnapshot->expects($this->never())->method('save');
        $this->mockEventBus->expects($this->once())->method('dispatch');
        $this->projector->expects($this->once())->method('project');
        $this->mockLogger->expects($this->once())->method('notice');

        $this->manager->save($aggregate);
    }

    /**
     * @dataProvider queryResult
     *
     * @param mixed $result
     */
    public function testShouldCheckIfClassExists($result, bool $expected): void
    {
        $stmt = $this->createMock(Statement::class);
        $stmt->method('fetch')->willReturn($result);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('expr')->willReturn($this->createMock(ExpressionBuilder::class));
        $queryBuilder->method('execute')->willReturn($stmt);
        $this->mockConnection->method('createQueryBuilder')->willReturn($queryBuilder);

        $this->assertEquals(
            $expected,
            $this->manager->exists(new AggregateId((string) Uuid::uuid4())),
        );
    }

    public function queryResult(): array
    {
        return [
            [[], false],
            [null, false],
            ['column', true],
        ];
    }

    public function testShouldDelete(): void
    {
        $aggregate = $this->createMock(AbstractAggregateRoot::class);
        $this->mockEventStore->expects($this->once())->method('delete');
        $this->mockConnection->expects($this->once())->method('delete');
        $this->mockSnapshot->expects($this->once())->method('delete');

        $this->manager->delete($aggregate);
    }
}
