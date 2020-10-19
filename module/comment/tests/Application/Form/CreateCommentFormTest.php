<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Application\Form;

use Ergonode\Comment\Application\Form\CreateCommentForm;
use Ergonode\Comment\Application\Form\Model\CreateCommentFormModel;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class CreateCommentFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'object_id' => '78750d71-b5d4-4e5b-a9df-25ff619e2148',
            'content' => 'Content',
        ];

        $object = new CreateCommentFormModel();
        $object->content = 'Content';
        $object->objectId = '78750d71-b5d4-4e5b-a9df-25ff619e2148';

        $objectToCompare = new CreateCommentFormModel();
        $form = $this->factory->create(CreateCommentForm::class, $objectToCompare);
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
