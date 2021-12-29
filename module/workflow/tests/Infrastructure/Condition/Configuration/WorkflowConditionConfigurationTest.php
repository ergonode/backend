<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Condition\Configuration;

use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfigurationParameterInterface;

class WorkflowConditionConfigurationTest extends TestCase
{
    public function testCreation(): void
    {
        $type = 'any type';
        $name = 'any name';
        $phrase = 'any phrase';
        $parameters = [$this->createMock(WorkflowConditionConfigurationParameterInterface::class)];

        $configuration = new WorkflowConditionConfiguration($type, $name, $phrase, $parameters);

        self::assertSame($type, $configuration->getType());
        self::assertSame($name, $configuration->getName());
        self::assertSame($phrase, $configuration->getPhrase());
        self::assertSame($parameters, $configuration->getParameters());
    }

    public function testCreationInvalidParameters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $type = 'any type';
        $name = 'any name';
        $phrase = 'any phrase';
        $parameters = [new \stdClass()];

        new WorkflowConditionConfiguration($type, $name, $phrase, $parameters);
    }
}
