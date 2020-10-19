<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateUnitAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateUnitAttributeCommandTest extends TestCase
{
    /**
     * @param AttributeId        $id
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param UnitId             $unitId
     * @param array              $groups
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        UnitId $unitId,
        array $groups
    ): void {
        $command = new UpdateUnitAttributeCommand($id, $label, $hint, $placeholder, $scope, $unitId, $groups);
        self::assertSame($id, $command->getId());
        self::assertSame($label, $command->getLabel());
        self::assertSame($hint, $command->getHint());
        self::assertSame($placeholder, $command->getPlaceholder());
        self::assertSame($groups, $command->getGroups());
        self::assertSame($scope, $command->getScope());
        self::assertSame($unitId, $command->getUnitId());
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
                $this->createMock(AttributeScope::class),
                $this->createMock(UnitId::class),
                [],
            ],
        ];
    }
}
