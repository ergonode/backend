<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Storage;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class FileStorage
{
    /**
     * @var string $directory ;
     */
    private string $directory;

    /**
     * @var resource
     */
    private $file;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->directory = sprintf('%s/export/', $kernel->getProjectDir());
    }

    /**
     * @param string $filename
     */
    public function create(string $filename): void
    {
        $this->file = \fopen(sprintf('%s/%s', $this->directory, $filename), 'wb');
        if (false === $this->file) {
            throw new \RuntimeException(sprintf('cant\' create "%s" file', $filename));
        }
    }

    /**
     * @param string $filename
     */
    public function open(string $filename): void
    {
        $this->file = \fopen(sprintf('%s/%s', $this->directory, $filename), 'ab');
        if (false === $this->file) {
            throw new \RuntimeException(sprintf('cant\' create "%s" file', $filename));
        }
    }

    /**
     * @param string[] $lines
     */
    public function append(array $lines): void
    {
        foreach ($lines as $line) {
            $result = \fwrite($this->file, $line);
            if (false === $result) {
                throw new \RuntimeException(sprintf('can\'t write to file'));
            }
        }
    }

    /**
     */
    public function close(): void
    {
        \fclose($this->file);
    }
}
