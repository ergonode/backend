<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Condition\Configuration\Parameter;

use Ergonode\Workflow\Infrastructure\Condition\Configuration\Parameter\TextWorkflowConditionConfigurationParameter;
use PHPUnit\Framework\TestCase;

class TextWorkflowConditionConfigurationParameterTest extends TestCase
{
    public function testCreation(): void
    {
        $name = 'any name';

        $parameter = new TextWorkflowConditionConfigurationParameter($name);

        self::assertSame($name, $parameter->getName());
    }
}
