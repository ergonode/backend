<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class TempFileStorage
{
    private string $directory;

    /**
     * @var resource
     */
    private $file;

    public function __construct(KernelInterface $kernel)
    {
        $this->directory = sprintf('%s/var/tmp', $kernel->getProjectDir());
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function exists(string $filename): bool
    {
        $filename = sprintf('%s/%s', $this->directory, $filename);

        return file_exists($filename);
    }

    public function create(string $filename): void
    {
        $file = sprintf('%s/%s', $this->directory, $filename);

        $fileName = basename($file);
        $folders = explode('/', str_replace('/'.$fileName, '', $file));

        $currentFolder = '';
        foreach ($folders as $folder) {
            $currentFolder .= $folder.DIRECTORY_SEPARATOR;
            if (!file_exists($currentFolder)) {
                mkdir($currentFolder, 0755);
            }
        }

        $this->file = \fopen($file, 'wb');

        if (false === $this->file) {
            throw new \RuntimeException(sprintf('cant\' create "%s" file', $filename));
        }
    }

    public function open(string $filename): void
    {
        $this->file = \fopen(sprintf('%s/%s', $this->directory, $filename), 'ab');
        if (false === $this->file) {
            throw new \RuntimeException(sprintf('can\'t create "%s" file', $filename));
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

    public function close(): void
    {
        \fclose($this->file);
    }

    public function clean(string $filename): void
    {
        $filename = sprintf('%s/%s', $this->directory, $filename);

        if (file_exists($filename)) {
            if (!is_dir($filename)) {
                unlink($filename);
            } else {
                if (substr($filename, strlen($filename) - 1, 1) !== '/') {
                    $filename .= '/';
                }
                $files = glob($filename.'*', GLOB_MARK);
                foreach ($files as $file) {
                    if (is_dir($file)) {
                        self::clean($file);
                    } else {
                        unlink($file);
                    }
                }
                rmdir($filename);
            }
        }
    }
}
