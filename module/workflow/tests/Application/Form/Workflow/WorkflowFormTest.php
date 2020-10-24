<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Application\Form\Workflow;

use Ergonode\Workflow\Application\Form\Workflow\WorkflowForm;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Application\Form\Model\Workflow\WorkflowFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Symfony\Component\Form\Test\TypeTestCase;

class WorkflowFormTest extends TypeTestCase
{
    public function testSupport(): void
    {
        $form = new WorkflowForm();
        self::assertTrue($form->supported(Workflow::TYPE));
        self::assertFalse($form->supported('unsupported type'));
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'code' => 'code',
            'statuses' => ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'],
        ];

        $object = new WorkflowFormModel();
        $object->code = 'code';
        $object->statuses = [new StatusId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new WorkflowFormModel();
        $form = $this->factory->create(WorkflowForm::class, $objectToCompare);
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
