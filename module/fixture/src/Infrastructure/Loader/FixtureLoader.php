<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Loader;

use Ergonode\Core\Application\AbstractModule;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 */
class FixtureLoader
{
    private const PATH = '%s/Resources/fixtures/%s/fixture.yaml';

    /**
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var string
     */
    private string $root;

    /**
     * @param KernelInterface $kernel
     * @param string          $root
     */
    public function __construct(KernelInterface $kernel, string $root)
    {
        $this->kernel = $kernel;
        $this->root = $root;
    }

    /**
     * @param string $group
     *
     * @return array
     */
    public function load(string $group = null): array
    {
        $files = [];
        $group = $group ?: '';

        foreach ($this->kernel->getBundles() as $bundle) {
            if ($bundle instanceof AbstractModule) {
                $file = str_replace('//', '/', sprintf(self::PATH, $bundle->getPath(), $group));
                if (file_exists($file)) {
                    $files[] = $file;
                }
            }
        }

        $file = str_replace('//', '/', sprintf(self::PATH, $this->root.'/src', $group));
        if (file_exists($file)) {
            $files[] = $file;
        }

        return $files;
    }
}
