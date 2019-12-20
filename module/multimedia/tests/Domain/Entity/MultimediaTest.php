<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Entity;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class MultimediaTest extends TestCase
{
    /**
     * @var MultimediaId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $crc;

    /**
     * @var string
     */
    private $mime;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(MultimediaId::class);
        $this->name = 'name';
        $this->extension = 'jpg';
        $this->size = 1234;
        $this->crc = 'crc';
        $this->mime = 'mime';
    }

    /**
     */
    public function testMultimediaCreation(): void
    {
        $multimedia = new Multimedia($this->id, $this->name, $this->extension, $this->size, $this->crc, $this->mime);
        $this->assertSame($this->id, $multimedia->getId());
        $this->assertSame($this->name, $multimedia->getName());
        $this->assertSame($this->extension, $multimedia->getExtension());
        $this->assertSame($this->size, $multimedia->getSize());
        $this->assertSame($this->crc, $multimedia->getCrc());
        $this->assertSame($this->mime, $multimedia->getMime());
        $this->assertSame(sprintf('%s.%s', $this->id, $this->extension), $multimedia->getFileName());
    }

    /**
     */
    public function testCreateFromFile(): void
    {
        $file = $this->createMock(File::class);
        $file->method('getExtension')->willReturn($this->extension);
        $file->method('getSize')->willReturn($this->size);
        $file->method('getMimeType')->willReturn($this->mime);
        $multimedia = Multimedia::createFromFile($this->id, $this->name, $file, $this->crc);
        $this->assertSame($this->name, $multimedia->getName());
        $this->assertSame($this->extension, $multimedia->getExtension());
        $this->assertSame($this->size, $multimedia->getSize());
        $this->assertSame($this->crc, $multimedia->getCrc());
        $this->assertSame($this->mime, $multimedia->getMime());
    }
}
