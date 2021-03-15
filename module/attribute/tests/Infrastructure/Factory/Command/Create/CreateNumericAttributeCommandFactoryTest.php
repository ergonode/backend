<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateNumericAttributeCommandFactory;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateNumericAttributeCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;

class CreateNumericAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateNumericAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(NumericAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {

        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateNumericAttributeCommandFactory();

        /** @var CreateNumericAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
    }
}
