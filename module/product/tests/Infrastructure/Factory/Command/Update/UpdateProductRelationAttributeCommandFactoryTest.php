<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\Product\Infrastructure\Factory\Command\Update\UpdateProductRelationAttributeCommandFactory;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Symfony\Component\Form\FormInterface;
use Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update\AbstractUpdateAttributeCommandFactoryTest;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class UpdateProductRelationAttributeCommandFactoryTest extends AbstractUpdateAttributeCommandFactoryTest
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateProductRelationAttributeCommandFactory();
        $this->assertTrue($commandFactory->support(ProductRelationAttribute::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $id = $this->createMock(AttributeId::class);
        $data = $this->getAttributeFormModel(AttributeFormModel::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateProductRelationAttributeCommandFactory();

        $result = $commandFactory->create($id, $form);

        $this->assertAttributeFormModel($id, $data, $result);
    }
}
