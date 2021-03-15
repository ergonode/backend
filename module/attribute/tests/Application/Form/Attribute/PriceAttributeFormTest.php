<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Form\Attribute;

use Ergonode\Attribute\Application\Form\Attribute\PriceAttributeForm;
use Ergonode\Attribute\Application\Model\Attribute\PriceAttributeFormModel;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\Core\Application\Form\Type\CurrencyFormType;
use Symfony\Component\Form\PreloadedExtension;

class PriceAttributeFormTest extends TypeTestCase
{
    private CurrencyQueryInterface $currencyQuery;

    public function setUp(): void
    {
        $this->currencyQuery = $this->createMock(CurrencyQueryInterface::class);
        $this->currencyQuery->method('getDictionary')->willReturn([
            'PLN' => 'PLN',
        ]);

        parent::setUp();
    }

    public function testSupport(): void
    {
        $form = new PriceAttributeForm();
        $this->assertTrue($form->supported(PriceAttribute::TYPE));
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

        $object = new PriceAttributeFormModel();
        $object->label = [];
        $object->placeholder = [];
        $object->hint = [];
        $object->scope = 'local';
        $object->code = 'code';
        $object->groups = ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'];

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
        $types[] = new CurrencyFormType($this->currencyQuery);

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
