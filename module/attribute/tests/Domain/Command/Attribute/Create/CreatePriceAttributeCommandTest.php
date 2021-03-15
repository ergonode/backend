<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\Create\CreatePriceAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Money\Currency;
use PHPUnit\Framework\TestCase;

class CreatePriceAttributeCommandTest extends TestCase
{
    /**
     * @param array $groups
     *
     * @throws \Exception
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(
        AttributeCode $attributeCode,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        array $groups,
        Currency $currency,
        AttributeScope $scope
    ): void {
        $command = new CreatePriceAttributeCommand(
            $attributeCode,
            $label,
            $hint,
            $placeholder,
            $scope,
            $currency,
            $groups
        );

        $this->assertSame($attributeCode, $command->getCode());
        $this->assertEquals(AttributeId::fromKey($attributeCode->getValue()), $command->getId());
        $this->assertSame($label, $command->getLabel());
        $this->assertSame($hint, $command->getHint());
        $this->assertSame($placeholder, $command->getPlaceholder());
        $this->assertSame($scope, $command->getScope());
        $this->assertSame($groups, $command->getGroups());
        $this->assertSame($currency, $command->getCurrency());
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(AttributeCode::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                [],
                new Currency('currency'),
                $this->createMock(AttributeScope::class),
            ],
        ];
    }
}
