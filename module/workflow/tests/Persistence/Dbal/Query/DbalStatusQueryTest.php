<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Persistence\Query\DbalStatusQuery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalStatusQueryTest extends TestCase
{
    /**
     * @var Connection|MockObject
     */
    private $mockConnection;

    /**
     * @var WorkflowProvider|MockObject
     */
    private $mockWorkflowProvider;

    /**
     * @var StatusRepositoryInterface|MockObject
     */
    private $mockStatusRepository;

    /**
     * @var DbalStatusQuery
     */
    private DbalStatusQuery $query;

    /**
     */
    protected function setUp(): void
    {
        $this->mockConnection = $this->createMock(Connection::class);
        $this->mockWorkflowProvider = $this->createMock(WorkflowProvider::class);
        $this->mockStatusRepository = $this->createMock(StatusRepositoryInterface::class);

        $this->query = new DbalStatusQuery(
            $this->mockConnection,
            $this->mockWorkflowProvider,
            $this->mockStatusRepository,
        );
    }

    /**
     */
    public function testShouldGetStatusCount(): void
    {
        $statusId1 = Uuid::uuid4()->toString();
        $statusId2 = Uuid::uuid4()->toString();
        $statusId3 = Uuid::uuid4()->toString();
        $statusId4 = Uuid::uuid4()->toString();
        $statusId5 = Uuid::uuid4()->toString();
        $statusStmt = $this->createMock(ResultStatement::class);
        $productStmt = $this->createMock(ResultStatement::class);
        $this->mockConnection
            ->method('executeQuery')
            ->willReturnOnConsecutiveCalls(
                $statusStmt,
                $productStmt,
            );
        $statusStmt
            ->method('fetchAll')
            ->willReturn([
                [
                    'id' => $statusId4,
                    'code' => 'cd4',
                    'label' => 'label4',
                ],
                [
                    'id' => $statusId1,
                    'code' => 'cd1',
                    'label' => 'label1',
                ],
                [
                    'id' => $statusId2,
                    'code' => 'cd2',
                    'label' => 'label2',
                ],
                [
                    'id' => $statusId5,
                    'code' => 'cd5',
                    'label' => 'label5',
                ],
                [
                    'id' => $statusId3,
                    'code' => 'cd3',
                    'label' => 'label3',
                ],
            ]);
        $productStmt
            ->method('fetchAll')
            ->willReturn([
                [
                    'value' => 'cd2',
                    'count' => 3,
                ],
            ]);
        $workflow = $this->createMock(Workflow::class);
        $this->mockWorkflowProvider
            ->method('provide')
            ->willReturn($workflow);
        $statusCode1 = $this->createMock(StatusCode::class);
        $statusCode1->method('getValue')->willReturn('cd1');
        $statusCode2 = $this->createMock(StatusCode::class);
        $statusCode2->method('getValue')->willReturn('cd2');
        $statusCode5 = $this->createMock(StatusCode::class);
        $statusCode5->method('getValue')->willReturn('cd5');
        $status1 = $this->createMock(Status::class);
        $status1->method('getCode')->willReturn($statusCode1);
        $status2 = $this->createMock(Status::class);
        $status2->method('getCode')->willReturn($statusCode2);
        $status5 = $this->createMock(Status::class);
        $status5->method('getCode')->willReturn($statusCode5);

        $this->mockStatusRepository
            ->method('load')
            ->willReturnOnConsecutiveCalls(
                $status1,
                $status1,
                $status2,
                $status2,
                $status5,
            );
        $transitionId1 = Uuid::uuid4()->toString();
        $transitionId2 = Uuid::uuid4()->toString();
        $transitions = [
            $transitionId1 =>
                new Transition(
                    new TransitionId($transitionId1),
                    new StatusId($statusId1),
                    new StatusId($statusId2)
                ),
            $transitionId2 =>
                new Transition(
                    new TransitionId($transitionId1),
                    new StatusId($statusId2),
                    new StatusId($statusId5)
                ),
        ];

        $workflow
            ->method('getTransitions')
            ->willReturn($transitions);

        $result = $this->query->getStatusCount(Language::fromString('en_EN'));

        $this::assertEquals(
            [
                [
                    'status_id' => $statusId1,
                    'code' => 'cd1',
                    'label' => 'label1',
                    'value' => 0,
                ],
                [
                    'status_id' => $statusId2,
                    'code' => 'cd2',
                    'label' => 'label2',
                    'value' => 3,
                ],
                [
                    'status_id' => $statusId5,
                    'code' => 'cd5',
                    'label' => 'label5',
                    'value' => 0,
                ],
                [
                    'status_id' => $statusId3,
                    'code' => 'cd3',
                    'label' => 'label3',
                    'value' => 0,
                ],

                [
                    'status_id' => $statusId4,
                    'code' => 'cd4',
                    'label' => 'label4',
                    'value' => 0,
                ],
            ],
            $result
        );
    }
}
