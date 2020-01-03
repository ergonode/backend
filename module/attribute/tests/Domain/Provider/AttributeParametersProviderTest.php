<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeParametersProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeParametersProviderTest extends TestCase
{
    /**
     */
    public function testProvidingAttributeParameters(): void
    {
        /** @var AbstractAttribute | MockObject $attribute */
        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getParameters')->willReturn(['data' => 'value', 'options' => 'options']);

        $provider = new AttributeParametersProvider();

        $this->assertSame(['data' => 'value'], $provider->provide($attribute));
    }
}
