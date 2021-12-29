<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Infrastructure\Persistence\Query\DbalStatusQuery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DbalStatusQueryTest extends TestCase
{
    private Connection $mockConnection;

    private DbalStatusQuery $query;

    protected function setUp(): void
    {
        $this->mockConnection = $this->createMock(Connection::class);

        $this->query = new DbalStatusQuery($this->mockConnection);
    }

    public function testShouldGetStatusCount(): void
    {
        $statusStmt = $this->createMock(ResultStatement::class);
        $this->mockConnection
            ->method('executeQuery')
            ->willReturn($statusStmt);
        $statusStmt
            ->method('fetchAllAssociative')
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
