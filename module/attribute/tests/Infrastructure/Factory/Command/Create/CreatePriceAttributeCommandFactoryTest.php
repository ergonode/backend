<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;
use Ergonode\Attribute\Application\Model\Attribute\Property\PriceAttributePropertyModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreatePriceAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreatePriceAttributeCommandFactory;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;

class CreatePriceAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreatePriceAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(PriceAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        /** @var PriceAttributeFormModel $data */
        $data = $this->getAttributeFormModel(PriceAttributeFormModel::class);
        $data->parameters = $this->createMock(PriceAttributePropertyModel::class);
        $data->parameters->currency = 'PLN';
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreatePriceAttributeCommandFactory();

        /** @var CreatePriceAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
        $this->assertSame($data->parameters->currency, $result->getCurrency()->getCode());
    }
}
