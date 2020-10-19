<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Application\Form\Tree;

use Ergonode\Category\Application\Form\Tree\CategoryTreeCreateForm;
use Ergonode\Category\Application\Model\Tree\CategoryTreeCreateFormModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class CategoryTreeCreateFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => ['pl_PL' => 'Any Name'],
            'code' => 'any_code',
        ];

        $object = new CategoryTreeCreateFormModel();
        $object->name = ['pl_PL' => 'Any Name'];
        $object->code = 'any_code';

        $objectToCompare = new CategoryTreeCreateFormModel();
        $form = $this->factory->create(CategoryTreeCreateForm::class, $objectToCompare);
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
