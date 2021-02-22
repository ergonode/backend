<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Security;

use Ergonode\Core\Application\Security\CacheAuthorizationChecker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CacheAuthorizationCheckerTest extends TestCase
{
    /**
     * @var AuthorizationCheckerInterface|MockObject
     */
    private $mockChecker;

    /**
     * @var TokenStorageInterface|MockObject
     */
    private $mockTokenStorage;

    private CacheAuthorizationChecker $cacheChecker;

    protected function setUp(): void
    {
        $this->mockChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->mockTokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->cacheChecker = new CacheAuthorizationChecker(
            $this->mockChecker,
            $this->mockTokenStorage,
        );
    }

    /**
     * @dataProvider supportedAttributesProvider
     *
     * @param object|string|null $attributes
     * @param object|string|null $subject
     */
    public function testShouldCache($attributes, $subject): void
    {
        $token = $this->createMock(TokenInterface::class);
        $this->mockTokenStorage->method('getToken')->willReturn($token);
        $this->mockChecker->expects($this->once())->method('isGranted')->willReturn(true);

        $resultFirst = $this->cacheChecker->isGranted($attributes, $subject);
        $resultSecond = $this->cacheChecker->isGranted($attributes, $subject);

        $this->assertTrue($resultFirst);
        $this->assertTrue($resultSecond);
    }

    /**
     * @dataProvider supportedAttributesProvider
     *
     * @param object|string|null $attributes
     * @param object|string|null $subject
     */
    public function testShouldNotCacheForCallsWithDifferentParameters($attributes, $subject): void
    {
        $token = $this->createMock(TokenInterface::class);
        $this->mockTokenStorage->method('getToken')->willReturn($token);

        $this->mockChecker
            ->expects($this->exactly($attributes === $subject ? 1 : 2))
            ->method('isGranted')
            ->willReturn(true);

        $resultFirst = $this->cacheChecker->isGranted($attributes, $subject);
        // reversed order in second call
        $resultSecond = $this->cacheChecker->isGranted($subject, $attributes);

        $this->assertTrue($resultFirst);
        $this->assertTrue($resultSecond);
    }

    public function supportedAttributesProvider(): array
    {
        return [
            [null, 'string'],
            ['string', null],
            [null, new \stdClass()],
            [new \stdClass(), null],
            ['string', 'string'],
            ['string', new \stdClass()],
            [new \stdClass(), 'string'],
            [new \stdClass(), new \stdClass()],
        ];
    }

    /**
     * @dataProvider notSupportedAttributesProvider
     *
     * @param mixed $attributes
     * @param mixed $subject
     */
    public function testShouldNotCacheForUnsupportedTypes($attributes, $subject): void
    {
        $token = $this->createMock(TokenInterface::class);
        $this->mockTokenStorage->method('getToken')->willReturn($token);

        $this->mockChecker
            ->expects($this->exactly(2))
            ->method('isGranted')
            ->willReturn(true);

        $resultFirst = $this->cacheChecker->isGranted($attributes, $subject);
        $resultSecond = $this->cacheChecker->isGranted($attributes, $subject);

        $this->assertTrue($resultFirst);
        $this->assertTrue($resultSecond);
    }

    public function notSupportedAttributesProvider(): array
    {
        return [
            [null, null],
            [null, 1],
            ['string', []],
            [new \stdClass(), 1.25],
            [[], null],
            [1, null],
            [1.25, null],
        ];
    }
}
