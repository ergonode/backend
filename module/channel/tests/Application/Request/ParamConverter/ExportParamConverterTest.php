<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Application\Request\ParamConverter;

use Ergonode\Channel\Application\Request\ParamConverter\ExportParamConverter;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class ExportParamConverterTest extends TestCase
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
     * @var ExportRepositoryInterface|MockObject
     */
    private $repository;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->repository = $this->createMock(ExportRepositoryInterface::class);
    }

    public function testSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn(Export::class);

        $paramConverter = new ExportParamConverter($this->repository);
        $this->assertTrue($paramConverter->supports($this->configuration));
    }

    public function testUnSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn('Any other class namespace');

        $paramConverter = new ExportParamConverter($this->repository);
        $this->assertFalse($paramConverter->supports($this->configuration));
    }

    public function testEmptyParameter(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);
        $this->request->method('get')->willReturn(null);

        $paramConverter = new ExportParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testInvalidParameter(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class);
        $this->request->method('get')->willReturn('incorrect uuid');

        $paramConverter = new ExportParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityNotExists(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());

        $paramConverter = new ExportParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityExists(): void
    {
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());
        $this->repository->method('load')->willReturn($this->createMock(Export::class));
        $this->request->attributes = $this->createMock(ParameterBag::class);
        $this->request->attributes->expects($this->once())->method('set');

        $paramConverter = new ExportParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }
}
