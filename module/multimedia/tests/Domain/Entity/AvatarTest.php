<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Entity;

use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AvatarTest extends TestCase
{
    /**
     * @var AvatarId|MockObject
     */
    private AvatarId $id;

    /**
     * @var string
     */
    private string $extension;

    /**
     * @var Hash|MockObject
     */
    private Hash $hash;

    /**
     * @var string
     */
    private string $mime;

    /**
     * @var int
     */
    private int $size;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(AvatarId::class);
        $this->extension = 'extension';
        $this->size = 123;
        $this->hash = $this->createMock(Hash::class);
        $this->hash->method('getValue')->willReturn('hash');
        $this->mime = 'text/json';
    }

    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        $avatar = $this->getClass();

        $this->assertEquals($this->id, $avatar->getId());
        $this->assertEquals(sprintf('hash.%s', $this->extension), $avatar->getFileName());
        $this->assertEquals($this->extension, $avatar->getExtension());
        $this->assertEquals($this->size, $avatar->getSize());
        $this->assertEquals($this->mime, $avatar->getMime());
        $this->assertEquals($this->hash, $avatar->getHash());
    }

    /**
     * @return Avatar
     */
    private function getClass(): Avatar
    {
        return new class(
            $this->id,
            $this->extension,
            $this->size,
            $this->hash,
            $this->mime
        ) extends Avatar {
        };
    }
}
