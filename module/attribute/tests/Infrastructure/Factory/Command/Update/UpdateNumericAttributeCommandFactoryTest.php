<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateNumericAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdateNumericAttributeCommandFactory;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;

class UpdateNumericAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateNumericAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(NumericAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateNumericAttributeCommandFactory();

        /** @var UpdateNumericAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
    }
}
