<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\Provider;

use Ergonode\Completeness\Domain\Calculator\Strategy\TemplateElementCompletenessStrategyInterface;
use Ergonode\Completeness\Domain\Provider\TemplateElementCompletenessStrategyProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateElementCompletenessStrategyProviderTest extends TestCase
{
    /**
     */
    public function testProvidingSupportedStrategy(): void
    {
        /** @var TemplateElementCompletenessStrategyInterface|MockObject $strategy */
        $strategy = $this->createMock(TemplateElementCompletenessStrategyInterface::class);
        $strategy->method('isSupported')->willReturn(true);

        $provider = new TemplateElementCompletenessStrategyProvider(...[$strategy]);
        $result = $provider->provide('Any Supported Type');
        $this->assertEquals($strategy, $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProvidingNotSupportedStrategy(): void
    {
        /** @var TemplateElementCompletenessStrategyInterface|MockObject $strategy */
        $strategy = $this->createMock(TemplateElementCompletenessStrategyInterface::class);
        $strategy->method('isSupported')->willReturn(false);

        $provider = new TemplateElementCompletenessStrategyProvider(...[$strategy]);
       $provider->provide('Any Not Supported Type');
    }
}
