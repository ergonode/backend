<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\RefreshToken\Doctrine;

use Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping\RefreshTokenMetadataFactory;
use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenManager;
use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RefreshTokenManagerTest extends TestCase
{
    /**
     * @var RefreshTokenRepositoryInterface|MockObject
     */
    private $mockRepository;
    /**
     * @var RefreshTokenMetadataFactory|MockObject
     */
    private $mockMetadataFactory;
    private RefreshTokenManager $manager;

    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(RefreshTokenRepositoryInterface::class);
        $this->mockMetadataFactory = $this->createMock(RefreshTokenMetadataFactory::class);

        $this->manager = new RefreshTokenManager(
            $this->mockRepository,
            $this->mockMetadataFactory,
        );
    }

    public function testShouldFlushData(): void
    {
        $this->mockRepository->expects($this->exactly(2))->method('insert');
        $this->mockRepository->expects($this->once())->method('delete');

        $persist = $this->createMock(RefreshToken::class);
        $this->manager->persist($persist);
        $this->manager->persist($persist);
        $this->manager->persist($this->createMock(RefreshToken::class));
        $toRemove = $this->createMock(RefreshToken::class);
        $toRemove->method('getId')->willReturn(1);
        $this->manager->remove($toRemove);
        $this->manager->remove($toRemove);

        $this->manager->flush();
        $this->manager->flush();
    }

    public function testShouldClear(): void
    {
        $this->mockRepository->expects($this->never())->method('insert');
        $this->mockRepository->expects($this->never())->method('delete');

        $this->manager->persist($this->createMock(RefreshToken::class));
        $toRemove = $this->createMock(RefreshToken::class);
        $toRemove->method('getId')->willReturn(true);
        $this->manager->remove($toRemove);

        $this->manager->clear();

        $this->manager->flush();
    }

    public function testContains(): void
    {
        $persisted = $this->createMock(RefreshToken::class);

        $this->manager->persist($persisted);

        $this->assertTrue(
            $this->manager->contains($persisted),
        );
        $this->assertFalse(
            $this->manager->contains($this->createMock(RefreshToken::class)),
        );
    }
}
