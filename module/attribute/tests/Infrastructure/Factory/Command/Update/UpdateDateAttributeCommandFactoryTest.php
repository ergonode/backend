<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdateDateAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateDateAttributeCommand;
use Ergonode\Attribute\Application\Model\Attribute\Property\DateAttributePropertyModel;
use Ergonode\Attribute\Application\Model\Attribute\DateAttributeFormModel;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;

class UpdateDateAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateDateAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(DateAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var DateAttributeFormModel $data */
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(DateAttributeFormModel::class);
        $data->parameters = $this->createMock(DateAttributePropertyModel::class);
        $data->parameters->format = DateFormat::YYYY_MM_DD;
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateDateAttributeCommandFactory();

        /** @var UpdateDateAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
        $this->assertSame($data->parameters->format, $result->getFormat()->getFormat());
    }
}
