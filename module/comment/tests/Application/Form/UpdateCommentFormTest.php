<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Application\Form;

use Ergonode\Comment\Application\Form\Model\UpdateCommentFormModel;
use Ergonode\Comment\Application\Form\UpdateCommentForm;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class UpdateCommentFormTest extends TypeTestCase
{
    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'content' => 'Content',
        ];

        $object = new UpdateCommentFormModel();
        $object->content = 'Content';

        $objectToCompare = new UpdateCommentFormModel();
        $form = $this->factory->create(UpdateCommentForm::class, $objectToCompare);
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
