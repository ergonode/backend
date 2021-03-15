<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdateUnitAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdateUnitAttributeCommand;
use Ergonode\Attribute\Application\Model\Attribute\Property\UnitAttributePropertyModel;
use Ergonode\Attribute\Application\Model\Attribute\UnitAttributeFormModel;
use Ramsey\Uuid\Uuid;

class UpdateUnitAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateUnitAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(UnitAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var UnitAttributeFormModel $data */
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(UnitAttributeFormModel::class);
        $data->parameters = $this->createMock(UnitAttributePropertyModel::class);
        $data->parameters->unit = Uuid::uuid4()->toString();
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateUnitAttributeCommandFactory();

        /** @var UpdateUnitAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
        $this->assertSame($data->parameters->unit, $result->getUnitId()->getValue());
    }
}
