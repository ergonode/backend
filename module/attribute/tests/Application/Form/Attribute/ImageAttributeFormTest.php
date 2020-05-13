<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\ImageAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;

/**
 */
class ImageAttributeFormTest extends TypeTestCase
{
    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private AttributeGroupQueryInterface $query;

    /**
     *
     */
    public function setUp(): void
    {
        $this->query = $this->createMock(AttributeGroupQueryInterface::class);
        $this->query->method('getAttributeGroupIds')->willReturn([
            '2ae47e1b-10c3-4dd6-ac70-41000125c29f',
        ]);

        parent::setUp();
    }

    /**
     */
    public function testSupport(): void
    {
        $form = new ImageAttributeForm();
        $this->assertTrue($form->supported(ImageAttribute::TYPE));
        $this->assertFalse($form->supported('unsupported type'));
    }

    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'code' => 'code',
            'label' => [],
            'placeholder' => [],
            'hint' => [],
            'multilingual' => true,
            'groups' => ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'],
        ];

        $object = new AttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->multilingual = true;
        $object->code = 'code';
        $object->groups = [new AttributeGroupId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new AttributeFormModel();
        $form = $this->factory->create(ImageAttributeForm::class, $objectToCompare);
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

    /**
     * @return array|PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $type = new AttributeGroupType($this->query);

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
