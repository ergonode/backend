<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command;

use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

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
     * @param bool               $system
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
        array $options,
        bool $system
    ): void
    {
        $command = new CreateAttributeCommand(
            $type,
            $attributeCode,
            $label,
            $hint,
            $placeholder,
            $multilingual,
            $groups,
            $parameters,
            $options,
            $system
        );
        $this->assertInstanceOf(AttributeId::class, $command->getId());
        $this->assertSame($type, $command->getType());
        $this->assertSame($attributeCode, $command->getCode());
        $this->assertEquals(AttributeId::fromKey($attributeCode), $command->getId());
        $this->assertSame($label, $command->getLabel());
        $this->assertSame($hint, $command->getHint());
        $this->assertSame($placeholder, $command->getPlaceholder());
        $this->assertSame($multilingual, $command->isMultilingual());
        $this->assertSame($groups, $command->getGroups());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertTrue($command->hasParameter('param_1'));
        $this->assertSame($parameters['param_1'], $command->getParameter('param_1'));
        $commandOptions = $command->getOptions();
        $this->assertSame($commandOptions['key_1'], $options[0]->value);
        $this->assertSame($commandOptions['key_2']->getValue()->getTranslations(), $options[1]->value);
        $this->assertSame($commandOptions['key_3']->getValue(), $options[2]->value);
        $this->assertSame($system, $command->isSystem());
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dataProvider(): array
    {
        $option1 = new AttributeOptionModel();
        $option1->key = 'key_1';
        $option1->value = null;
        $option2 = new AttributeOptionModel();
        $option2->key = 'key_2';
        $option2->value = ['EN' => 'english', 'PL' => 'polish'];
        $option3 = new AttributeOptionModel();
        $option3->key = 'key_3';
        $option3->value = 'option';

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
                [$option1, $option2, $option3],
                true,
            ],
        ];
    }
}
