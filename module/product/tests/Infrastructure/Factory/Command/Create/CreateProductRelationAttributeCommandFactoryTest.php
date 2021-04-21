<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Product\Infrastructure\Factory\Command\Create\CreateProductRelationAttributeCommandFactory;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create\AbstractCreateAttributeCommandFactoryTest;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class CreateProductRelationAttributeCommandFactoryTest extends AbstractCreateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new CreateProductRelationAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(ProductRelationAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateProductRelationAttributeCommandFactory();

        $result = $commandFactory->create($form);

        $this->assertAttributeFormModel($data, $result);
    }
}
