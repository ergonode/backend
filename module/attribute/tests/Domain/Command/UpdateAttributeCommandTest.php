<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateAttributeCommandTest extends TestCase
{
    /**
     * @param AttributeId        $id
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param array              $groups
     * @param array              $parameters
     * @param array              $options
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        array $groups,
        array $parameters,
        array $options
    ): void {
        $command = new UpdateAttributeCommand($id, $label, $hint, $placeholder, $groups, $parameters, $options);
        $this->assertSame($id, $command->getId());
        $this->assertSame($label, $command->getLabel());
        $this->assertSame($hint, $command->getHint());
        $this->assertSame($placeholder, $command->getPlaceholder());
        $this->assertSame($groups, $command->getGroups());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertSame($options, $command->getOptions());
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
                [],
                [],
                [],
            ],
        ];
    }
}
