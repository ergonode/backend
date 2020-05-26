<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\GalleryAttributeForm;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class GalleryAttributeFormTest extends TypeTestCase
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
        $form = new GalleryAttributeForm();
        $this->assertTrue($form->supported(GalleryAttribute::TYPE));
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
            'scope' => 'local',
            'groups' => ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'],
        ];

        $object = new AttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->scope = 'local';
        $object->code = 'code';
        $object->groups = [new AttributeGroupId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new AttributeFormModel();
        $form = $this->factory->create(GalleryAttributeForm::class, $objectToCompare);
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
