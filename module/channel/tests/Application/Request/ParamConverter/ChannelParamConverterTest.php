<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Application\Request\ParamConverter;

use Ergonode\Channel\Application\Request\ParamConverter\ChannelParamConverter;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChannelParamConverterTest extends TestCase
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
     * @var ChannelRepositoryInterface|MockObject
     */
    private $repository;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->repository = $this->createMock(ChannelRepositoryInterface::class);
    }

    public function testSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn(AbstractChannel::class);

        $paramConverter = new ChannelParamConverter($this->repository);
        self::assertTrue($paramConverter->supports($this->configuration));
    }

    public function testUnSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn('Any other class namespace');

        $paramConverter = new ChannelParamConverter($this->repository);
        self::assertFalse($paramConverter->supports($this->configuration));
    }

    public function testEmptyParameter(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->request->method('get')->willReturn(null);

        $paramConverter = new ChannelParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testInvalidParameter(): void
    {
        $this->expectException(BadRequestHttpException::class);
        $this->request->method('get')->willReturn('incorrect uuid');

        $paramConverter = new ChannelParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityNotExists(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());

        $paramConverter = new ChannelParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }

    public function testEntityExists(): void
    {
        $this->request->method('get')->willReturn(Uuid::uuid4()->toString());
        $this->repository->method('load')->willReturn($this->createMock(AbstractChannel::class));
        $this->request->attributes = $this->createMock(ParameterBag::class);
        $this->request->attributes->expects(self::once())->method('set');

        $paramConverter = new ChannelParamConverter($this->repository);
        $paramConverter->apply($this->request, $this->configuration);
    }
}
