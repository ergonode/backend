<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Tests\Infrastructure\Action;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Infrastructure\Provider\TemplateProvider;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Provider\ProductFactoryProvider;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\ProductSimple\Infrastructure\Action\Extension\ProductAttributeExtension;
use Ergonode\ProductSimple\Infrastructure\Action\Extension\ProductCategoryExtension;
use Ergonode\ProductSimple\Infrastructure\Action\ProductSimpleImportAction;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductSimpleImportActionTest extends TestCase
{
    /**
     * @var TemplateProvider|MockObject
     */
    private $templateProvider;

    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private $productRepository;

    /**
     * @var ProductQueryInterface|MockObject
     */
    private $productQuery;

    /**
     * @var ProductAttributeExtension|MockObject
     */
    private $attributeExtension;

    /**
     * @var ProductCategoryExtension|MockObject
     */
    private $categoryExtension;

    /**
     * @var ProductFactoryProvider|MockObject
     */
    private $productFactoryProvider;

    /**
     * @var Record|MockObject
     */
    private $record;

    /**
     * @var ProductSimpleImportAction
     */
    private $action;

    protected function setUp()
    {
        $this->templateProvider = $this->createMock(TemplateProvider::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productQuery = $this->createMock(ProductQueryInterface::class);
        $this->attributeExtension = $this->createMock(ProductAttributeExtension::class);
        $this->categoryExtension = $this->createMock(ProductCategoryExtension::class);
        $this->productFactoryProvider = $this->createMock(ProductFactoryProvider::class);
        $this->record = $this->createMock(Record::class);
        $this->action = new ProductSimpleImportAction(
            $this->templateProvider,
            $this->productRepository,
            $this->productQuery,
            $this->attributeExtension,
            $this->categoryExtension,
            $this->productFactoryProvider
        );
    }

    /**
     *      * @expectedException InvalidArgumentException
     */
    public function testNoSkuAction()
    {
        $this->record->expects($this->once())->method('get')->willReturn(null);
        $this->action->action($this->record);
    }

    /**
     */
    public function testAction()
    {
        $this->record->expects($this->exactly(2))->method('get')->willReturn(new StringValue('sku'));
        $this->attributeExtension->expects($this->once())->method('extend')->willReturn(
            [
                'attributes' => ['color' => new StringValue('example')],
                'categories' => [new CategoryCode('example')],
            ]
        );
        $template = $this->createMock(Template::class);
        $templateId = $this->createMock(TemplateId::class);
        $template->expects($this->once())->method('getId')->willReturn($templateId);
        $this->templateProvider->expects($this->once())->method('provide')->willReturn($template);
        $productFactoryInterface = $this->createMock(ProductFactoryInterface::class);
        $productFactoryInterface->expects($this->any())->method('create')->with($this->isInstanceOf(ProductId::class), new Sku('sku'), $templateId, [new CategoryCode('example')], ['color' => new StringValue('example')]);
        $this->productFactoryProvider->expects($this->once())->method('provide')->willReturn($productFactoryInterface);
        $this->productRepository->expects($this->once())->method('save');
        $this->action->action($this->record);
    }

    /**
     */
    public function testAction2()
    {
        $this->record->expects($this->any())->method('get')->willReturn(new StringValue('string'));
        $this->record->expects($this->any())->method('has')->willReturn(true);
        $this->attributeExtension->expects($this->once())->method('extend')->willReturn(
            [
                'attributes' => ['color' => new StringValue('example')],
                'categories' => [new CategoryCode('example')],
            ]
        );
        $template = $this->createMock(Template::class);
        $templateId = $this->createMock(TemplateId::class);
        $template->expects($this->once())->method('getId')->willReturn($templateId);
        $this->templateProvider->expects($this->once())->method('provide')->willReturn($template);
        $productFactoryInterface = $this->createMock(ProductFactoryInterface::class);
        $productFactoryInterface->expects($this->any())->method('create')->with($this->isInstanceOf(ProductId::class), new Sku('string'), $templateId, [new CategoryCode('example')], ['color' => new StringValue('example')]);
        $this->productFactoryProvider->expects($this->once())->method('provide')->willReturn($productFactoryInterface);
        $this->productRepository->expects($this->once())->method('save');
        $this->action->action($this->record);
    }

    /**
     * @expectedException \Ergonode\Transformer\Infrastructure\Exception\ProcessorException
     */
    public function testNoProductAction()
    {
        $this->record->expects($this->any())->method('get')->willReturn(new StringValue('sku'));
        $this->attributeExtension->expects($this->once())->method('extend')->willReturn(
            [
                'attributes' => ['color' => new StringValue('example')],
                'categories' => [new CategoryCode('example')],
            ]
        );
        $this->productQuery->expects($this->once())->method('findBySku')->willReturn(['id' => 'a499cff6-e402-4d7b-b370-7b1850620871']);
        $this->action->action($this->record);
    }
}
