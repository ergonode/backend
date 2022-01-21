<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Controller\Api\Condition;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Application\Controller\Api\Condition\WorkflowConditionReadAction;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionDictionaryProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkflowConditionReadActionTest extends TestCase
{
    /**
     * @var WorkflowConditionDictionaryProvider|MockObject
     */
    private $mockProvider;

    private WorkflowConditionReadAction $action;

    protected function setUp(): void
    {
        $this->mockProvider = $this->createMock(WorkflowConditionDictionaryProvider::class);

        $this->action = new WorkflowConditionReadAction($this->mockProvider);
    }

    public function testAction(): void
    {
        $this->mockProvider->expects(self::once())->method('getDictionary')->willReturn([]);
        $language = new Language('en_GB');

        $action = $this->action;

        $response = $action($language);

        $this->assertEquals([], $response);
    }
}
