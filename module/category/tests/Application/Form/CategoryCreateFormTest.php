<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Application\Form;

use Ergonode\Category\Application\Form\CategoryForm;
use Ergonode\Category\Application\Model\CategoryFormModel;
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
            'name' => ['pl_PL' =>  'Any Name'],
            'code' => 'any_code',
        ];

        $object = new CategoryFormModel();
        $object->name = ['pl_PL' =>  'Any Name'];
        $object->code = new CategoryCode('any_code');

        $objectToCompare = new CategoryFormModel();
        $form = $this->factory->create(CategoryForm::class, $objectToCompare);
        $form->submit($formData);

        self::assertTrue($form->isSynchronized());
        self::assertTrue($form->isValid());
        self::assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            self::assertArrayHasKey($key, $children);
        }
    }
}
