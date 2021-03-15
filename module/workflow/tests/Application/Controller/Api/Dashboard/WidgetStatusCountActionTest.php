<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Controller\Api\Dashboard;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Application\Controller\Api\Dashboard\WidgetStatusCountAction;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WidgetStatusCountActionTest extends TestCase
{
    /**
     * @var StatusQueryInterface|MockObject
     */
    private $mockQuery;

    private WidgetStatusCountAction $controller;

    protected function setUp(): void
    {
        $this->mockQuery = $this->createMock(StatusQueryInterface::class);

        $this->controller = new WidgetStatusCountAction(
            $this->mockQuery,
        );
    }

    public function testShouldGetStatuses(): void
    {
        $this->mockQuery
            ->method('getStatusCount')
            ->willReturn(['status data']);

        $response = ($this->controller)(
            new Language('en_EN'),
            new Language('pl_PL'),
        );

        $this->assertEquals(
            ['status data'],
            $response->getContent(),
        );
    }
}
