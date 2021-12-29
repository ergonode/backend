<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Condition\Configuration\Parameter;

use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Condition\Configuration\Parameter\MultiSelectWorkflowConditionConfigurationParameter;

class MultiSelectWorkflowConditionConfigurationParameterTest extends TestCase
{
    public function testCreation(): void
    {
        $name = 'any name';
        $options = ['a' => 'option 1'];

        $parameter = new MultiSelectWorkflowConditionConfigurationParameter($name, $options);

        self::assertSame($name, $parameter->getName());
        self::assertSame($options, $parameter->getOptions());
    }
}
