<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Application\Form;

use Ergonode\Note\Application\Form\Model\UpdateNoteFormModel;
use Ergonode\Note\Application\Form\UpdateNoteForm;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class UpdateNoteFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'content' => 'Content',
        ];

        $object = new UpdateNoteFormModel();
        $object->content = 'Content';

        $objectToCompare = new UpdateNoteFormModel();
        $form = $this->factory->create(UpdateNoteForm::class, $objectToCompare);
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
