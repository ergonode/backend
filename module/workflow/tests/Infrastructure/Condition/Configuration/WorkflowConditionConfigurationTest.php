<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Condition\Configuration;

use Ergonode\Workflow\Infrastructure\Condition\Configuration\WorkflowConditionConfiguration;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Infrastructure\Condition\Configuration\WorkflowConditionConfigurationParameterInterface;

class WorkflowConditionConfigurationTest extends TestCase
{
    public function testCreation(): void
    {
        $name = 'any name';
        $phrase = 'any phrase';
        $parameters = [$this->createMock(WorkflowConditionConfigurationParameterInterface::class)];

        $configuration = new WorkflowConditionConfiguration($name, $phrase, $parameters);

        self::assertSame($name, $configuration->getName());
        self::assertSame($phrase, $configuration->getPhrase());
        self::assertSame($parameters, $configuration->getParameters());
    }

    public function testCreationInvalidParameters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $name = 'any name';
        $phrase = 'any phrase';
        $parameters = [new \stdClass()];

        new WorkflowConditionConfiguration($name, $phrase, $parameters);
    }
}
