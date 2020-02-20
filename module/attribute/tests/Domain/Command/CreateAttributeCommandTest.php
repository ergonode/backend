<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command;

use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;

/**
 */
class CreateAttributeCommandTest extends TestCase
{
    /**
     * @param AttributeType      $type
     * @param AttributeCode      $attributeCode
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param array              $groups
     * @param bool               $multilingual
     * @param array              $parameters
     * @param array              $options
     *
     * @dataProvider dataProvider
     *
     * @throws \Exception
     */
    public function testCreateCommand(
        AttributeType $type,
        AttributeCode $attributeCode,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        array $groups,
        bool $multilingual,
        array $parameters,
        array $options
    ): void {
        $command = new CreateAttributeCommand(
            $type,
            $attributeCode,
            $label,
            $hint,
            $placeholder,
            $multilingual,
            $groups,
            $parameters,
            $options
        );

        $this->assertSame($type, $command->getType());
        $this->assertSame($attributeCode, $command->getCode());
        $this->assertEquals(AttributeId::fromKey($attributeCode->getValue()), $command->getId());
        $this->assertSame($label, $command->getLabel());
        $this->assertSame($hint, $command->getHint());
        $this->assertSame($placeholder, $command->getPlaceholder());
        $this->assertSame($multilingual, $command->isMultilingual());
        $this->assertSame($groups, $command->getGroups());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertTrue($command->hasParameter('param_1'));
        $this->assertSame($parameters['param_1'], $command->getParameter('param_1'));
        $commandOptions = $command->getOptions();

        $this->assertSame($commandOptions['key_1'], $options['key_1']);
        $this->assertSame($commandOptions['key_2'], $options['key_2']);
        $this->assertSame($commandOptions['key_3'], $options['key_3']);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dataProvider(): array
    {
        $option1 = $this->createMock(OptionInterface::class);
        $option2 = $this->createMock(OptionInterface::class);
        $option3 = $this->createMock(OptionInterface::class);

        return [
            [
                $this->createMock(AttributeType::class),
                $this->createMock(AttributeCode::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
                [],
                true,
                ['param_1' => 'parameter'],
                ['key_1' => $option1, 'key_2' => $option2, 'key_3' => $option3],
            ],
        ];
    }
}
