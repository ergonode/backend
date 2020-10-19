<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\DateAttributeForm;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Application\Model\Attribute\DateAttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 */
class DateAttributeFormTest extends TypeTestCase
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
        $form = new DateAttributeForm();
        self::assertTrue($form->supported(DateAttribute::TYPE));
        self::assertFalse($form->supported('unsupported type'));
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

        $object = new DateAttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->scope = 'local';
        $object->code = 'code';
        $object->groups = [new AttributeGroupId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new DateAttributeFormModel();
        $form = $this->factory->create(DateAttributeForm::class, $objectToCompare);
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
