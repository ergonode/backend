<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Application\Form;

use Ergonode\Category\Application\Form\CategoryCreateForm;
use Ergonode\Category\Application\Model\CategoryCreateFormModel;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class CategoryCreateFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => ['PL' =>  'Any Name'],
            'code' => 'any_code',
        ];

        $object = new CategoryCreateFormModel();
        $object->name = ['PL' =>  'Any Name'];
        $object->code = new CategoryCode('any_code');

        $objectToCompare = new CategoryCreateFormModel();
        $form = $this->factory->create(CategoryCreateForm::class, $objectToCompare);
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
