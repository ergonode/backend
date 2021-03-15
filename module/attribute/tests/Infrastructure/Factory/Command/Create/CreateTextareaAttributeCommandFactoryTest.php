<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Application\Model\Attribute\TextareaAttributeFormModel;
use Ergonode\Attribute\Application\Model\Attribute\Property\TextareaAttributePropertyModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateTextareaAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateTextareaAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;

class CreateTextareaAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateTextareaAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(TextareaAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var TextareaAttributeFormModel $data */
        $data = $this->getAttributeFormModel(TextareaAttributeFormModel::class);
        $data->parameters = $this->createMock(TextareaAttributePropertyModel::class);
        $data->parameters->richEdit = true;
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateTextareaAttributeCommandFactory();

        /** @var CreateTextareaAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
        $this->assertTrue($result->richEdit());
    }
}
