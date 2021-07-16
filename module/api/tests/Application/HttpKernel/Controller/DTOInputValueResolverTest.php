<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Tests\Application\HttpKernel\Controller;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\HttpKernel\Controller\DTOInputValueResolver;
use Ergonode\SharedKernel\Application\Api\DTOInputInterface;
use Ergonode\SharedKernel\Application\Serializer\Exception\DeserializationException;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOInputValueResolverTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $mockSerializer;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $mockValidator;

    private DTOInputValueResolver $resolver;

    protected function setUp(): void
    {
        $this->mockSerializer = $this->createMock(SerializerInterface::class);
        $this->mockValidator = $this->createMock(ValidatorInterface::class);

        $this->resolver = new DTOInputValueResolver(
            [
                'application/json' => 'json',
            ],
            $this->mockSerializer,
            $this->mockValidator,
        );
    }

    public function testResolve(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], 'body');

        $this->mockSerializer
            ->method('deserialize')
            ->with('body', get_class($dto), 'json')
            ->willReturn($dto);

        $this->mockValidator
            ->method('validate')
            ->willReturn($this->createMock(ConstraintViolationList::class));

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertTrue($this->resolver->supports($request, $argument));
        $this->assertEquals(1, count($result));
        $this->assertSame($dto, $result[0]);
    }

    public function testResolveVariadic(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            true,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], 'body');

        $this->mockSerializer
            ->method('deserialize')
            ->with('body', get_class($dto).'[]', 'json')
            ->willReturn([$dto, $dto]);

        $this->mockValidator
            ->method('validate')
            ->willReturn($this->createMock(ConstraintViolationList::class));

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertTrue($this->resolver->supports($request, $argument));
        $this->assertEquals(2, count($result));
        $this->assertSame([$dto, $dto], $result);
    }

    public function testResolveThrowsExceptionForUnknownContentType(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/weirdo'], 'body');

        $this->expectExceptionMessage('The "Content-Type" unknown');

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsExceptionForMissingContentType(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], [], 'body');

        $this->expectExceptionMessage('The "Content-Type" header must exist');

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsExceptionForMissingBody(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->expectExceptionMessage('Missing request body');

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsExceptionForSerializerException(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], 'body');

        $exception = new DeserializationException('Serializer exception');
        $this->mockSerializer
            ->method('deserialize')
            ->with('body', get_class($dto), 'json')
            ->willThrowException($exception);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Serializer exception');

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    public function testResolveThrowsExceptionForViolations(): void
    {
        $dto = new class() implements DTOInputInterface {
        };
        $argument = new ArgumentMetadata(
            'name',
            get_class($dto),
            false,
            false,
            null,
        );
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], 'body');

        $this->mockSerializer
            ->method('deserialize')
            ->with('body', get_class($dto), 'json')
            ->willReturn($dto);

        $violations = $this->createMock(ConstraintViolationList::class);
        $violations->method('count')->willReturn(3);
        $this->mockValidator
            ->method('validate')
            ->willReturn($violations);
        $this->expectException(ViolationsHttpException::class);

        iterator_to_array($this->resolver->resolve($request, $argument));
    }

    /**
     * @dataProvider notSupportedArgumentMetadataProvider
     */
    public function testNotSupport(ArgumentMetadata $argumentMetadata): void
    {
        $this->assertFalse($this->resolver->supports(
            new Request(),
            $argumentMetadata,
        ));
    }

    /**
     * @return ArgumentMetadata[][]
     */
    public function notSupportedArgumentMetadataProvider(): array
    {
        return [
            'no type' => [new ArgumentMetadata('name', null, false, false, null)],
            'no command type' => [new ArgumentMetadata('name', self::class, false, false, null)],
            'string type' => [new ArgumentMetadata('name', 'string', false, false, null)],
        ];
    }
}
