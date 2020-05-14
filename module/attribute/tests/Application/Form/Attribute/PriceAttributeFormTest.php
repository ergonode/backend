<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\PriceAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Ergonode\Attribute\Application\Form\Type\AttributeGroupType;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Application\Form\Type\CurrencyFormType;
use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;

/**
 */
class PriceAttributeFormTest extends TypeTestCase
{
    /**
     * @var AttributeGroupQueryInterface|MockObject
     */
    private AttributeGroupQueryInterface $groupQuery;

    /**
     * @var CurrencyQueryInterface
     */
    private CurrencyQueryInterface $currencyQuery;

    /**
     *
     */
    public function setUp(): void
    {
        $this->groupQuery = $this->createMock(AttributeGroupQueryInterface::class);
        $this->groupQuery->method('getAttributeGroupIds')->willReturn([
            '2ae47e1b-10c3-4dd6-ac70-41000125c29f',
        ]);

        $this->currencyQuery = $this->createMock(CurrencyQueryInterface::class);
        $this->currencyQuery->method('getDictionary')->willReturn([
            'PLN' => 'PLN',
        ]);

        parent::setUp();
    }

    /**
     */
    public function testSupport(): void
    {
        $form = new PriceAttributeForm();
        $this->assertTrue($form->supported(PriceAttribute::TYPE));
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

        $object = new PriceAttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->multilingual = true;
        $object->code = 'code';
        $object->groups = [new AttributeGroupId('2ae47e1b-10c3-4dd6-ac70-41000125c29f')];

        $objectToCompare = new PriceAttributeFormModel();
        $form = $this->factory->create(PriceAttributeForm::class, $objectToCompare);
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
        $types[] = new CurrencyFormType($this->currencyQuery);

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
