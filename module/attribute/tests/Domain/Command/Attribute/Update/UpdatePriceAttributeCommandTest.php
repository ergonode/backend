<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command\Attribute\Update;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdatePriceAttributeCommand;
use Money\Currency;

/**
 */
class UpdatePriceAttributeCommandTest extends TestCase
{
    /**
     * @param AttributeId        $id
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param Currency           $currency
     * @param array              $groups
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        Currency $currency,
        array $groups
    ): void {
        $command = new UpdatePriceAttributeCommand($id, $label, $hint, $placeholder, $multilingual, $currency, $groups);
        $this->assertSame($id, $command->getId());
        $this->assertSame($label, $command->getLabel());
        $this->assertSame($hint, $command->getHint());
        $this->assertSame($placeholder, $command->getPlaceholder());
        $this->assertSame($groups, $command->getGroups());
        $this->assertSame($multilingual, $command->isMultilingual());
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
                $this->createMock(AttributeId::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                true,
                new Currency('Currency'),
                [],
            ],
        ];
    }
}
