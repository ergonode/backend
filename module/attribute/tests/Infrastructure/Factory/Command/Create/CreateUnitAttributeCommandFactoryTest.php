<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Application\Model\Attribute\UnitAttributeFormModel;
use Ergonode\Attribute\Application\Model\Attribute\Property\UnitAttributePropertyModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateUnitAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateUnitAttributeCommandFactory;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;

class CreateUnitAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateUnitAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(UnitAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var UnitAttributeFormModel $data */
        $data = $this->getAttributeFormModel(UnitAttributeFormModel::class);
        $data->parameters = $this->createMock(UnitAttributePropertyModel::class);
        $data->parameters->unit = Uuid::uuid4()->toString();
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateUnitAttributeCommandFactory();

        /** @var CreateUnitAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
        $this->assertSame($data->parameters->unit, $result->getUnitId()->getValue());
    }
}
