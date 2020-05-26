<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Application\Request\ParamConverter;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Application\Request\ParamConverter\AbstractProductParamConverter;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 */
class AbstractProductParamConverterTest extends TestCase
{
    /**
     * @var Request|MockObject
     */
    private $request;

    /**
     * @var ParamConverter|MockObject
     */
    private $configuration;

    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
    }

    /**
     */
    public function testSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn(AbstractProduct::class);

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $this->assertTrue($paramConverter->supports($this->configuration));
    }

    /**
     */
    public function testUnSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn('Any other class namespace');

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $this->assertFalse($paramConverter->supports($this->configuration));
    }

    /**
     */
    public function testEmptyParameter(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);
        $this->request->method('get')->willReturn(null);

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    /**
     */
    public function testInvalidParameter(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);
        $this->request->method('get')->willReturn('incorrect uuid');

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    /**
     */
    public function testEntityNotExists(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    /**
     */
    public function testEntityExists(): void
    {
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());
        $this->repository->method('load')->willReturn($this->createMock(AbstractProduct::class));
        $this->request->attributes = $this->createMock(ParameterBag::class);
        $this->request->attributes->expects($this->once())->method('set');

        $paramConverter = new AbstractProductParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }
}
