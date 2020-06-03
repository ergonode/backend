<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Multimedia\Tests\Infrastructure\Provider;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use League\Flysystem\FilesystemInterface;
use Ergonode\Multimedia\Infrastructure\Storage\FilesystemMultimediaStorage;

/**
 */
class SimpleMultimediaFileProviderTest extends TestCase
{
    /**
     * @var FilesystemInterface|MockObject
     */
    private $filesystem;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var string
     */
    private string $filename;

    /**
     */
    protected function setUp(): void
    {
        /** @var MultimediaId|MockObject $id */
        $this->filename = 'File name';
        $this->content = 'File content';
        /** @var FilesystemInterface|MockObject $kernel */
        $this->filesystem = $this->createMock(FilesystemInterface::class);
    }

    /**
     */
    public function testFileRead(): void
    {
        $this->filesystem->method('read')->willReturn($this->content);

        $storage = new FilesystemMultimediaStorage($this->filesystem);
        $result = $storage->read($this->filename);

        $this->assertSame($this->content, $result);
    }

    /**
     */
    public function testFileWrite(): void
    {
        $this->filesystem->expects($this->once())->method('write');

        $storage = new FilesystemMultimediaStorage($this->filesystem);
        $result = $storage->write($this->filename, $this->content);
    }

    /**
     */
    public function testFileExists(): void
    {
        $this->filesystem->method('has')->willReturn(true);

        $storage = new FilesystemMultimediaStorage($this->filesystem);
        $result = $storage->has($this->filename);

        $this->assertTrue($result);
    }

    /**
     */
    public function testFileInfo(): void
    {
        $size = 100;
        $mime = 'any mime';

        $this->filesystem->method('getSize')->willReturn($size);
        $this->filesystem->method('getMimetype')->willReturn($mime);

        $storage = new FilesystemMultimediaStorage($this->filesystem);
        $result = $storage->info($this->filename);

        $this->assertEquals(['size' => $size, 'mime' => $mime], $result);
    }
}
