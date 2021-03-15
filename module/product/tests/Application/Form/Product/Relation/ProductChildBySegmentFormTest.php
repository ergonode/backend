<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Form\Product\Relation;

use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\Product\Application\Form\Product\Relation\ProductChildBySegmentsForm;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySegmentsFormModel;

class ProductChildBySegmentFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'segments' => ['78750d71-b5d4-4e5b-a9df-25ff619e2148'],
        ];

        $object = new ProductChildBySegmentsFormModel();
        $object->segments = ['78750d71-b5d4-4e5b-a9df-25ff619e2148'];

        $objectToCompare = new ProductChildBySegmentsFormModel();
        $form = $this->factory->create(ProductChildBySegmentsForm::class, $objectToCompare);
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
