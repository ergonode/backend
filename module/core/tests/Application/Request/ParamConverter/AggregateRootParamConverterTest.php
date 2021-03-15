<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Request\ParamConverter;

use Ergonode\Core\Application\Request\ParamConverter\AggregateRootParamConverter;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;

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
     * @var EventStoreManagerInterface|MockObject
     */
    private EventStoreManagerInterface $manager;

    private AbstractAggregateRoot $classA;

    private AbstractAggregateRoot $classB;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->manager = $this->createMock(EventStoreManagerInterface::class);
        $this->classA = $this->getClassA();
        $this->classB = $this->getClassB();
    }

    public function testSupportedClass(): void
    {
        $paramConverter = new AggregateRootParamConverter($this->manager);
        $this->configuration->method('getClass')->willReturn($this->classA);
        self::assertTrue($paramConverter->supports($this->configuration));
    }

    public function testUnsupportedClass(): void
    {
        $paramConverter = new AggregateRootParamConverter($this->manager);
        $this->configuration->method('getClass')->willReturn(\stdClass::class);
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
        $classA = $this->getClassA();
        $this->expectException(\InvalidArgumentException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn($classA);
        $this->request->method('get')->willReturn(null);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testNotStringParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn($this->classA);
        $this->request->method('get')->willReturn(234);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testInvalidParameter(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn($this->classA);
        $this->request->method('get')->willReturn('test');

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn($this->classA);
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn(null);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testIncorrectEntity(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(get_class($this->classA));
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn($this->classB);

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityExists(): void
    {
        $this->configuration->method('getName')->willReturn('test');
        $this->configuration->method('getClass')->willReturn(get_class($this->classA));
        $this->request->method('get')->willReturn('afe5ca9f-c3ce-4732-bb50-a5bc109fa823');
        $this->manager->method('load')->willReturn($this->classA);
        $this->request->attributes = $this->createMock(ParameterBag::class);
        $this->request->attributes->expects(self::once())->method('set');

        $paramConverter = new AggregateRootParamConverter($this->manager);
        $paramConverter->apply($this->request, $this->configuration);
    }

    private function getClassA(): AbstractAggregateRoot
    {
        return new class() extends AbstractAggregateRoot {
            public function getId(): AggregateId
            {
                return AggregateId::generate();
            }
        };
    }

    private function getClassB(): AbstractAggregateRoot
    {
        return new class() extends AbstractAggregateRoot {
            public function getId(): AggregateId
            {
                return AggregateId::generate();
            }
        };
    }
}
