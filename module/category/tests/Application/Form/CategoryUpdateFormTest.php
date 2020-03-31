<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Application\Form;

use Ergonode\Category\Application\Form\CategoryUpdateForm;
use Ergonode\Category\Application\Model\CategoryUpdateFormModel;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class CategoryUpdateFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => ['pl_PL' =>  'Any Name'],
        ];

        $object = new CategoryUpdateFormModel();
        $object->name = ['pl_PL' =>  'Any Name'];

        $objectToCompare = new CategoryUpdateFormModel();
        $form = $this->factory->create(CategoryUpdateForm::class, $objectToCompare);
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
