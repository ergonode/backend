<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\Update\UpdatePriceAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdatePriceAttributeCommand;
use Ergonode\Attribute\Application\Model\Attribute\Property\PriceAttributePropertyModel;
use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;

class UpdatePriceAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdatePriceAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(PriceAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var PriceAttributeFormModel $data */
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(PriceAttributeFormModel::class);
        $data->parameters = $this->createMock(PriceAttributePropertyModel::class);
        $data->parameters->currency = 'PLN';
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdatePriceAttributeCommandFactory();

        /** @var UpdatePriceAttributeCommand $result */
        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
        $this->assertSame($data->parameters->currency, $result->getCurrency()->getCode());
    }
}
