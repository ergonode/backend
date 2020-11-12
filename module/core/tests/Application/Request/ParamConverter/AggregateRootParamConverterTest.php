<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Request\ParamConverter;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Core\Application\Request\ParamConverter\AggregateRootParamConverter;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AggregateRootParamConverterTest extends TestCase
{
    /**
     * @var Request|MockObject
     */
    private Request $request;

    /**
     * @var ParamConverter|MockObject
     */
    private ParamConverter $configuration;

    /**
     * @var EventStoreManager|MockObject
     */
    private EventStoreManager $manager;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->manager = $this->createMock(EventStoreManager::class);
    }

    public function testSupportedClass(): void
    {
        $paramConverter = new AggregateRootParamConverter($this->manager);
        $this->configuration->method('getClass')->willReturn(AbstractCategory::class);
        self::assertTrue($paramConverter->supports($this->configuration));
    }

    public function testUnsupportedClass(): void
    {
        $paramConverter = new AggregateRootParamConverter($this->manager);
        $this->configuration->method('getClass')->willReturn('Any other class namespace');
        self::assertFalse($paramConverter->supports($this->configuration));
    }

    public function testEmptyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->configuration->method('getName')->willReturn(null);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEmptyParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn(null);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testNotStringParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn(234);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testInvalidParameter(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn('test');

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn(null);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testIncorrectEntity(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn($this->createMock(SimpleProduct::class));

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityExists(): void
    {
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(Category::class);
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn($this->createMock(Category::class));
        $this->request->attributes = $this->createMock(ParameterBag::class);
        $this->request->attributes->expects(self::once())->method('set');

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }
}
