<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Controller\Api\Condition;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Application\Controller\Api\Condition\WorkflowConditionConfigurationReadAction;
use Ergonode\Workflow\Infrastructure\Condition\Configuration\WorkflowConditionConfiguration;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionConfigurationProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkflowConditionConfigurationReadActionTest extends TestCase
{
    /**
     * @var WorkflowConditionConfigurationProvider|MockObject
     */
    private $mockProvider;

    private WorkflowConditionConfigurationReadAction $action;

    protected function setUp(): void
    {
        $this->mockProvider = $this->createMock(WorkflowConditionConfigurationProvider::class);

        $this->action = new WorkflowConditionConfigurationReadAction($this->mockProvider);
    }

    public function testAction(): void
    {
        $configuration = $this->createMock(WorkflowConditionConfiguration::class);
        $this->mockProvider->expects(self::once())->method('getConfiguration')->willReturn($configuration);
        $language = new Language('en_GB');
        $condition = 'TEST';

        $action = $this->action;

        $response = $action($language, $condition);

        $this->assertEquals($configuration, $response);
    }

    public function testNoConfig(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->mockProvider->expects(self::once())->method('getConfiguration')->will(
            $this->throwException(new \RuntimeException())
        );
        $language = new Language('en_GB');
        $condition = 'TEST';

        $action = $this->action;

        $action($language, $condition);
    }
}
