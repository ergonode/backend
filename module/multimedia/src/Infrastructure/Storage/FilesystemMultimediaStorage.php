<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Storage;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FileExistsException;

/**
 */
class FilesystemMultimediaStorage implements MultimediaStorageInterface
{
    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $storage;

    /**
     * @param FilesystemInterface $multimediaStorage
     */
    public function __construct(FilesystemInterface $multimediaStorage)
    {
        $this->storage = $multimediaStorage;
    }

    /**
     * @param string $filename
     *
     * @return string
     *
     * @throws FileNotFoundException
     */
    public function read(string $filename): string
    {
        return $this->storage->read($filename);
    }

    /**
     * @param string $filename
     *
     * @return resource
     *
     * @throws FileNotFoundException
     */
    public function readStream(string $filename)
    {
        return $this->storage->readStream($filename);
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @throws FileExistsException
     */
    public function write(string $filename, string $content): void
    {
        $this->storage->write($filename, $content);
    }

    /**
     * @param string $filename
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function info(string $filename): array
    {
        return [
            'mime' =>  $this->storage->getMimetype($filename),
            'size' => $this->storage->getSize($filename),
        ];
    }

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function has(string $filename): bool
    {
        return $this->storage->has($filename);
    }
}
