<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Form\Product\Binding;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\Product\Application\Form\Product\Binding\ProductBindForm;

class ProductBindFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $product = $this->createMock(AbstractProduct::class);
        $formData = [
            'bind_id' => '78750d71-b5d4-4e5b-a9df-25ff619e2148',
        ];

        $object = new ProductBindFormModel($product);
        $object->bindId = '78750d71-b5d4-4e5b-a9df-25ff619e2148';

        $objectToCompare = new ProductBindFormModel($product);
        $form = $this->factory->create(ProductBindForm::class, $objectToCompare);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
