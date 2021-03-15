<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class AbstractMultimediaTest extends TestCase
{
    /**
     * @var MultimediaId|MockObject
     */
    private MultimediaId $id;

    private string $filename;

    private string $extension;

    /**
     * @var Hash|MockObject
     */
    private Hash $hash;

    private string $mime;

    private int $size;

    protected function setUp(): void
    {
        $this->id = $this->createMock(MultimediaId::class);
        $this->filename = 'filename';
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
        $multimedia = $this->getClass();

        $this->assertEquals($this->id, $multimedia->getId());
        $this->assertEquals(sprintf('hash.%s', $this->extension), $multimedia->getFileName());
        $this->assertEquals($this->filename, $multimedia->getName());
        $this->assertEquals($this->extension, $multimedia->getExtension());
        $this->assertEquals($this->size, $multimedia->getSize());
        $this->assertEquals($this->mime, $multimedia->getMime());
        $this->assertEquals($this->hash, $multimedia->getHash());
    }

    /**
     * @throws \Exception
     */
    public function testAltManipulation(): void
    {
        $newAlt = $this->createMock(TranslatableString::class);
        $newAlt->method('isEqual')->willReturn(false);

        $multimedia = $this->getClass();
        $oldAlt = $multimedia->getAlt();
        $this->assertNotSame($newAlt, $multimedia->getAlt());
        $multimedia->changeAlt($newAlt);
        $this->assertNotSame($oldAlt, $multimedia->getAlt());
        $this->assertSame($newAlt, $multimedia->getAlt());
    }

    private function getClass(): AbstractMultimedia
    {
        return new class(
            $this->id,
            $this->filename,
            $this->extension,
            $this->size,
            $this->hash,
            $this->mime
        ) extends AbstractMultimedia {
        };
    }
}
