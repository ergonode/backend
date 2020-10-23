<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Infrastructure\Persistence\Query\DbalStatusQuery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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

    private DbalStatusQuery $query;

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

    public function testShouldGetStatusCount(): void
    {
        $statusStmt = $this->createMock(ResultStatement::class);
        $this->mockConnection
            ->method('executeQuery')
            ->willReturn($statusStmt);
        $statusStmt
            ->method('fetchAll')
            ->willReturn([
                [
                    'status_id' => $id4 = (string) Uuid::uuid4(),
                    'code' => 'cd4',
                    'label' => 'label4',
                    'value' => 0,
                ],
                [
                    'status_id' => $id1 = (string) Uuid::uuid4(),
                    'code' => 'cd1',
                    'label' => 'label1',
                    'value' => 0,
                ],
                [
                    'status_id' => $id2 = (string) Uuid::uuid4(),
                    'code' => 'cd2',
                    'label' => 'label2',
                    'value' => 3,
                ],
                [
                    'status_id' => $id3 = (string) Uuid::uuid4(),
                    'code' => 'cd3',
                    'label' => 'label3',
                    'value' => 0,
                ],
                [
                    'status_id' => $id2,
                    'code' => 'cd2',
                    'label' => 'label2',
                    'value' => 0,
                ],
            ]);
        $workflow = $this->createMock(Workflow::class);
        $this->mockWorkflowProvider
            ->method('provide')
            ->willReturn($workflow);
        $workflow
            ->method('getSortedTransitionStatuses')
            ->willReturn([
                new StatusId($id3),
                new StatusId($id1),
            ]);

        $result = $this->query->getStatusCount(Language::fromString('en_EN'), Language::fromString('pl_PL'));

        $this->assertEquals(
            [
                [
                    'status_id' => $id3,
                    'code' => 'cd3',
                    'label' => 'label3',
                    'value' => 0,
                ],
                [
                    'status_id' => $id1,
                    'code' => 'cd1',
                    'label' => 'label1',
                    'value' => 0,
                ],
                [
                    'status_id' => $id2,
                    'code' => 'cd2',
                    'label' => 'label2',
                    'value' => 3,
                ],
                [
                    'status_id' => $id4,
                    'code' => 'cd4',
                    'label' => 'label4',
                    'value' => 0,
                ],
            ],
            $result
        );
    }
}
