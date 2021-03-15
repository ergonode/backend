<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Application\Model\Attribute\DateAttributeFormModel;
use Ergonode\Attribute\Application\Model\Attribute\Property\DateAttributePropertyModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateDateAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateDateAttributeCommandFactory;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;

class CreateDateAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateDateAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(DateAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var DateAttributeFormModel $data */
        $data = $this->getAttributeFormModel(DateAttributeFormModel::class);
        $data->parameters = $this->createMock(DateAttributePropertyModel::class);
        $data->parameters->format = DateFormat::YYYY_MM_DD;
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateDateAttributeCommandFactory();

        /** @var CreateDateAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
        $this->assertSame($data->parameters->format, $result->getFormat()->getFormat());
    }
}
