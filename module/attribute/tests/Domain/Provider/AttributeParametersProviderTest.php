<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeParametersProvider;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
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
        $unitRepository = $this->createMock(UnitRepositoryInterface::class);
        $attribute->method('getParameters')->willReturn(['data' => 'value', 'options' => 'options']);
        $unitRepository->method('load')->willReturn($this->createMock(Unit::class));

        $provider = new AttributeParametersProvider($unitRepository);

        $this->assertSame(['data' => 'value'], $provider->provide($attribute));
    }
}
