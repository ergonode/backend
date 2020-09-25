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
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Persistence\Dbal\Query\DbalStatusQuery;
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
     * @var DbalStatusQuery
     */
    private DbalStatusQuery $query;

    /**
     */
    protected function setUp(): void
    {
        $this->mockConnection = $this->createMock(Connection::class);
        $this->mockWorkflowProvider = $this->createMock(WorkflowProvider::class);

        $this->query = new DbalStatusQuery(
            $this->mockConnection,
            $this->mockWorkflowProvider,
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
                    'id' => $statusId1,
                    'code' => 'cd4',
                    'label' => 'label4',
                ],
                [
                    'id' => $statusId2,
                    'code' => 'cd1',
                    'label' => 'label1',
                ],
                [
                    'id' => $statusId3,
                    'code' => 'cd2',
                    'label' => 'label2',
                ],
                [
                    'id' => $statusId4,
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
        $workflow
            ->method('getSortedTransitionStatuses')
            ->willReturn([
                new StatusId($statusId3),
                new StatusId($statusId1),
            ]);

        $result = $this->query->getStatusCount(Language::fromString('en_EN'));

        $this->assertEquals(
            [
                [
                    'status_id' => $statusId3,
                    'code' => 'cd3',
                    'label' => 'label3',
                    'value' => 0,
                ],
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
