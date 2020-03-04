<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration\Provider;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class MigrationDirectoryProvider implements MigrationDirectoryProviderInterface
{
    private const DIRECTORY = 'migrations';

    /**
     * @var string[]
     */
    private array $directories = [];

    /**
     * @param string          $path
     * @param KernelInterface $kernel
     */
    public function __construct(string $path, KernelInterface $kernel)
    {
        $mainDirectory = realpath(sprintf('%s%s', $path, self::DIRECTORY));
        if ($mainDirectory) {
            $this->directories[] = $mainDirectory;
        }

        foreach ($kernel->getBundles() as $bundle) {
            if (false !== strpos($bundle->getNamespace(), 'Ergonode\\')) {
                $directory = sprintf('%s/../%s', $bundle->getPath(), self::DIRECTORY);
                if (file_exists($directory)) {
                    $this->directories[] = realpath($directory);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getDirectoryCollection(): array
    {
        return $this->directories;
    }

    /**
     * @return string
     */
    public function getMainDirectory(): string
    {
        return reset($this->directories);
    }
}
