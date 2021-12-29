<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Form\Attribute;

use Ergonode\Product\Application\Form\Attribute\ProductRelationAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class ProductRelationAttributeFormTest extends TypeTestCase
{
    public function testSupport(): void
    {
        $form = new ProductRelationAttributeForm();
        self::assertTrue($form->supported(ProductRelationAttribute::TYPE));
        self::assertFalse($form->supported('unsupported type'));
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'code' => 'code',
            'label' => [],
            'placeholder' => [],
            'hint' => [],
            'scope' => 'local',
            'groups' => ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'],
        ];

        $object = new AttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->scope = 'local';
        $object->code = 'code';
        $object->groups = ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'];

        $objectToCompare = new AttributeFormModel();
        $form = $this->factory->create(ProductRelationAttributeForm::class, $objectToCompare);
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
