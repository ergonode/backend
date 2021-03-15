<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeParametersProvider;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AttributeParametersProviderTest extends TestCase
{
    public function testProvidingAttributeParameter(): void
    {
        $unit = $this->createMock(Unit::class);
        $unit->method('getSymbol')->willReturn('W');
        $unitRepository = $this->createMock(UnitRepositoryInterface::class);
        $unitRepository->method('load')->willReturn($unit);
        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getParameters')->willReturn(
            [
                'options' => 1,
                'unit' => '8fbc1fd6-34cb-4792-9820-66011c2a97b9',
            ]
        );

        $provider = new AttributeParametersProvider($unitRepository);
        $this->assertSame(['unit' => 'W'], $provider->provide($attribute));
    }
}
