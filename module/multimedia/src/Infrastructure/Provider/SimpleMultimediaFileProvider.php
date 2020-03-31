<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class SimpleMultimediaFileProvider implements MultimediaFileProviderInterface
{
    private const PATH = '%s/public/multimedia/%s';

    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFile(string $filename): string
    {
        return \sprintf(self::PATH, $this->kernel->getProjectDir(), $filename);
    }

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function hasFile(string $filename): bool
    {
        return file_exists($this->getFile($filename));
    }
}
