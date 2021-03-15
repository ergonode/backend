<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Form\Product;

use Ergonode\Category\Application\Form\Type\CategoryType;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Product\Application\Form\Product\SimpleProductForm;
use Ergonode\Product\Application\Model\Product\SimpleProductFormModel;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\Product\Domain\Entity\SimpleProduct;

class SimpleProductFormTest extends TypeTestCase
{
    /**
     * @var CategoryQueryInterface|MockObject
     */
    private CategoryQueryInterface $query;

    public function setUp(): void
    {
        $this->query = $this->createMock(CategoryQueryInterface::class);
        $this->query->method('getDictionary')->willReturn([
            '2ae47e1b-10c3-4dd6-ac70-41000125c29f' => 'category',
        ]);

        parent::setUp();
    }

    public function testSupported(): void
    {
        $form = new SimpleProductForm();
        $this->assertTrue($form->supported(SimpleProduct::TYPE));
        $this->assertFalse($form->supported('Any incorrect type'));
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'templateId' => '78750d71-b5d4-4e5b-a9df-25ff619e2148',
            'sku' => 'sku',
            'categoryIds' => ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'],
        ];

        $object = new SimpleProductFormModel();
        $object->sku = 'sku';
        $object->template = '78750d71-b5d4-4e5b-a9df-25ff619e2148';
        $object->categories = ['2ae47e1b-10c3-4dd6-ac70-41000125c29f'];

        $objectToCompare = new SimpleProductFormModel();
        $form = $this->factory->create(SimpleProductForm::class, $objectToCompare);
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
        // create a type instance with the mocked dependencies
        $type = new CategoryType($this->query);

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
        ];
    }
}
