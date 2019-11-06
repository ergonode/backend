<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Application\Form;

use Ergonode\Note\Application\Form\CreateNoteForm;
use Ergonode\Note\Application\Form\Model\CreateNoteFormModel;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class CreateNoteFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'author_id' => '78750d71-b5d4-4e5b-a9df-25ff619e2148',
            'object_id' => '78750d71-b5d4-4e5b-a9df-25ff619e2148',
            'content' => 'Content',
        ];

        $object = new CreateNoteFormModel();
        $object->content = 'Content';
        $object->objectId = '78750d71-b5d4-4e5b-a9df-25ff619e2148';
        $object->authorId = '78750d71-b5d4-4e5b-a9df-25ff619e2148';

        $objectToCompare = new CreateNoteFormModel();
        $form = $this->factory->create(CreateNoteForm::class, $objectToCompare);
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

