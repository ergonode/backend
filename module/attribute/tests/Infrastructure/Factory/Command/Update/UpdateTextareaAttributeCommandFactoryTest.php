<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdateTextareaAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateTextareaAttributeCommand;
use Ergonode\Attribute\Application\Model\Attribute\Property\TextareaAttributePropertyModel;
use Ergonode\Attribute\Application\Model\Attribute\TextareaAttributeFormModel;

class UpdateTextareaAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateTextareaAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(TextareaAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var TextareaAttributeFormModel $data */
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(TextareaAttributeFormModel::class);
        $data->parameters = $this->createMock(TextareaAttributePropertyModel::class);
        $data->parameters->richEdit = true;
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateTextareaAttributeCommandFactory();

        /** @var UpdateTextareaAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
        $this->assertTrue($result->isRichEdit());
    }
}
