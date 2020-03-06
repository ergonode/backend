<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class FileFaker extends BaseProvider
{
    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var string
     */
    private string $root;

    /**
     * @param Generator       $generator
     * @param KernelInterface $kernel
     * @param string          $root
     */
    public function __construct(Generator $generator, KernelInterface $kernel, string $root)
    {
        parent::__construct($generator);

        $this->kernel = $kernel;
        $this->root = $root;
    }

    /**
     * @param string $file
     *
     * @return File
     */
    public function multimediaFile(string $file): File
    {
        $cacheDir = $this->kernel->getCacheDir();

        $path = sprintf('%s/%s', $this->root, $file);
        $tmp = sprintf('%s/%s', $cacheDir, basename($file));

        copy($path, $tmp);

        return new File($tmp);
    }
}
