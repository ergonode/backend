<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionConfigurationInterface;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionDictionaryProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WorkflowConditionDictionaryProviderTest extends TestCase
{
    /**
     * @var WorkflowConditionConfigurationInterface|MockObject
     */
    private $mockStrategy;

    private WorkflowConditionDictionaryProvider $provider;

    protected function setUp(): void
    {
        $this->mockStrategy = $this->createMock(WorkflowConditionConfigurationInterface::class);

        $this->provider = new WorkflowConditionDictionaryProvider([$this->mockStrategy]);
    }

    public function testDictionary(): void
    {
        $config = $this->createMock(WorkflowConditionConfiguration::class);
        $config->method('getType')->willReturn('NEW');
        $config->method('getName')->willReturn('test');

        $this->mockStrategy->method('getConfiguration')->willReturn($config);

        $language = new Language('en_GB');

        $provider = $this->provider;

        $result = $provider->getDictionary($language);

        self::assertEquals(['NEW' => 'test'], $result);
    }
}
