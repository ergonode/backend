<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\UnitAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\UnitAttributeFormModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Core\Application\Form\Type\UnitIdFormType;
use Ergonode\Core\Domain\Query\UnitQueryInterface;

/**
 */
class UnitAttributeFormTest extends TypeTestCase
{
    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private AttributeGroupQueryInterface $groupQuery;

    /**
     * @var UnitQueryInterface
     */
    private UnitQueryInterface $unitQuery;

    /**
     *
     */
    public function setUp(): void
    {
        $this->groupQuery = $this->createMock(AttributeGroupQueryInterface::class);
        $this->groupQuery->method('getAttributeGroupIds')->willReturn([
            '2ae47e1b-10c3-4dd6-ac70-41000125c29f',
        ]);

        $this->unitQuery = $this->createMock(UnitQueryInterface::class);
        $this->unitQuery->method('getAllUnitIds')->willReturn([
            '9948b184-57ba-4dd7-9aee-7fe81312ef94',
        ]);

        parent::setUp();
    }

    /**
     */
    public function testSupport(): void
    {
        $form = new UnitAttributeForm();
        $this->assertTrue($form->supported(UnitAttribute::TYPE));
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

        $object = new UnitAttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->scope = 'local';
        $object->code = 'code';
        $object->groups = [new AttributeGroupId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new UnitAttributeFormModel();
        $form = $this->factory->create(UnitAttributeForm::class, $objectToCompare);
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
        $types[] = new AttributeGroupType($this->groupQuery);
        $types[] = new UnitIdFormType($this->unitQuery);

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
