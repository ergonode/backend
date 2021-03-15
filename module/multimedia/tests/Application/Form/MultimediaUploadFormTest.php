<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Form;

use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Symfony\Component\Form\Test\TypeTestCase;

class MultimediaUploadFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'upload' => null,
        ];

        $object = new MultimediaUploadModel();
        $object->upload = null;

        $objectToCompare = new MultimediaUploadModel();
        $form = $this->factory->create(MultimediaUploadForm::class, $objectToCompare);
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
