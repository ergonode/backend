<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Form\Tree;

use Ergonode\Category\Application\Form\Tree\CategoryTreeUpdateForm;
use Ergonode\Category\Application\Model\Tree\CategoryTreeUpdateFormModel;
use Ergonode\Category\Application\Model\Tree\TreeNodeFormModel;
use Symfony\Component\Form\Test\TypeTestCase;

class CategoryTreeUpdateFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => ['pl_PL' => 'Any Name'],
            'categories' => [
                [
                    new TreeNodeFormModel(),
                ],
            ],
        ];

        $object = new CategoryTreeUpdateFormModel();
        $object->name = ['pl_PL' => 'Any Name'];
        $object->categories = [
            new TreeNodeFormModel(),
        ];

        $objectToCompare = new CategoryTreeUpdateFormModel();
        $form = $this->factory->create(CategoryTreeUpdateForm::class, $objectToCompare);
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
