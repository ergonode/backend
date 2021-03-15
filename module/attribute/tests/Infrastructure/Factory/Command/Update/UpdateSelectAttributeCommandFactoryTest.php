<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdateSelectAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateSelectAttributeCommand;

class UpdateSelectAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateSelectAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(SelectAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateSelectAttributeCommandFactory();

        /** @var UpdateSelectAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
    }
}
