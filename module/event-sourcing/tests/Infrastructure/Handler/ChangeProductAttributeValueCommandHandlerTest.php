<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Tests\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Editor\Infrastructure\Handler\ChangeProductAttributeValueCommandHandler;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

/**
 */
class ChangeProductAttributeValueCommandHandlerTest extends TestCase
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private $repository;

    /**
     * @var ValueManipulationService
     */
    private $service;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    private $language;

    private $command;

    /**
     */
    public function setUp()
    {
        $this->repository = $this->createMock(ProductDraftRepositoryInterface::class);
        $this->service = $this->createMock(ValueManipulationService::class);
        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->language = $this->createMock(Language::class);
        $this->command = $this->createMock(ChangeProductAttributeValueCommand::class);
        $this->command->method('getLanguage')->willReturn($this->language);
        $this->command->method('getId')->willReturn($this->createMock(ProductDraftId::class));
        $this->command->method('getAttributeId')->willReturn($this->createMock(AttributeId::class));
    }

    /**
     */
    public function testInvokeRemovingAttr()
    {
        $handler = new ChangeProductAttributeValueCommandHandler($this->repository, $this->service, $this->attributeRepository);
        $draft = ($this->createMock(ProductDraft::class));
        $this->repository->method('load')->willReturn($draft);
        $draft->method('hasAttribute')->willReturn(true);
        $this->attributeRepository->method('load')->willReturn($this->createMock(AbstractAttribute::class));
        $this->command->method('getValue')->willReturn('');
        $draft->expects($this->once())->method('removeAttribute');
        $draft->expects($this->never())->method('addAttribute');
        $draft->expects($this->never())->method('changeAttribute');
        $handler->__invoke($this->command);
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @param bool   $multilingual
     *
     * @dataProvider dataProvider
     */
    public function testInvokeNewValue($value, string $type, bool $multilingual)
    {
        $handler = new ChangeProductAttributeValueCommandHandler($this->repository, $this->service, $this->attributeRepository);
        $this->language->method('getCode')->willReturn('EN');
        $draft = ($this->createMock(ProductDraft::class));
        $this->repository->method('load')->willReturn($draft);
        $this->command->method('getValue')->willReturn($value);
        $attribute = $this->createMock($type);
        $this->attributeRepository->method('load')->willReturn($attribute);
        $attribute->method('getCode')->willReturn($this->createMock(AttributeCode::class));
        $attribute->method('isMultilingual')->willReturn($multilingual);
        $draft->method('hasAttribute')->willReturn(true);
        $draft->method('getAttribute')->willReturn($this->createMock(StringValue::class));
        $this->service->method('calculate')->willReturn($this->createMock(StringValue::class));
        $draft->expects($this->once())->method('changeAttribute');
        $draft->expects($this->never())->method('addAttribute');
        $draft->expects($this->never())->method('removeAttribute');
        $handler->__invoke($this->command);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'value' => ['example'],
                'type' => MultiSelectAttribute::class,
                'multilingual' => false,
            ],
            [
                'value' => 'example',
                'type' => SelectAttribute::class,
                'multilingual' => false,
            ],
            [
                'value' => 'example',
                'type' => TextAttribute::class,
                'multilingual' => true,
            ],
            [
                'value' => 'example',
                'type' => TextAttribute::class,
                'multilingual' => false,
            ],
        ];
    }
}
