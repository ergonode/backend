<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateDateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateDateAttributeCommandTest extends TestCase
{
    /**
     * @param AttributeId        $id
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param DateFormat         $format
     * @param array              $groups
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope     $scope,
        DateFormat $format,
        array $groups
    ): void {
        $command = new UpdateDateAttributeCommand($id, $label, $hint, $placeholder, $scope, $format, $groups);
        self::assertSame($id, $command->getId());
        self::assertSame($label, $command->getLabel());
        self::assertSame($hint, $command->getHint());
        self::assertSame($placeholder, $command->getPlaceholder());
        self::assertSame($groups, $command->getGroups());
        self::assertSame($scope, $command->getScope());
        self::assertSame($format, $command->getFormat());
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
                $this->createMock(DateFormat::class),
                [],
            ],
        ];
    }
}
