<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\NumericAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\Test\TypeTestCase;

class NumericAttributeFormTest extends TypeTestCase
{
    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private AttributeGroupQueryInterface $query;

    public function setUp(): void
    {
        $this->query = $this->createMock(AttributeGroupQueryInterface::class);
        $this->query->method('getAttributeGroupIds')->willReturn([
            '2ae47e1b-10c3-4dd6-ac70-41000125c29f',
        ]);

        parent::setUp();
    }

    public function testSupport(): void
    {
        $form = new NumericAttributeForm();
        $this->assertTrue($form->supported(NumericAttribute::TYPE));
        $this->assertFalse($form->supported('unsupported type'));
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
        $form = $this->factory->create(NumericAttributeForm::class, $objectToCompare);
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
