<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Form;

use Ergonode\Category\Application\Form\CategoryForm;
use Ergonode\Category\Application\Model\CategoryFormModel;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryCreateFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => ['pl_PL' =>  'Any Name'],
            'code' => 'any_code',
        ];

        $object = new CategoryFormModel();
        $object->name = ['pl_PL' =>  'Any Name'];
        $object->code = 'any_code';

        $objectToCompare = new CategoryFormModel();
        $form = $this->factory->create(CategoryForm::class, $objectToCompare);
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
