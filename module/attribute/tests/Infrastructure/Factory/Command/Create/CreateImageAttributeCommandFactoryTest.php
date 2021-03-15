<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateImageAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateImageAttributeCommandFactory;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;

class CreateImageAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateImageAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(ImageAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateImageAttributeCommandFactory();

        /** @var CreateImageAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
    }
}
