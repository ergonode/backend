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
use Ergonode\Workflow\Persistence\Dbal\Query\DbalStatusQuery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DbalStatusQueryTest extends TestCase
{
    /**
     * @var Connection|MockObject
     */
    private $mockConnection;

    /**
     * @var DbalStatusQuery
     */
    private DbalStatusQuery $query;

    /**
     */
    protected function setUp(): void
    {
        $this->mockConnection = $this->createMock(Connection::class);

        $this->query = new DbalStatusQuery(
            $this->mockConnection,
        );
    }

    /**
     */
    public function testShouldGetStatusCount(): void
    {
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
                    'id' => '1',
                    'code' => 'cd1',
                    'label' => 'label1',
                ],
                [
                    'id' => '2',
                    'code' => 'cd2',
                    'label' => 'label2',
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

        $result = $this->query->getStatusCount(Language::fromString('en_EN'));

        $this->assertEquals(
            [
                [
                    'status_id' => '1',
                    'code' => 'cd1',
                    'label' => 'label1',
                    'value' => 0,
                ],
                [
                    'status_id' => '2',
                    'code' => 'cd2',
                    'label' => 'label2',
                    'value' => 3,
                ],
            ],
            $result
        );
    }
}
