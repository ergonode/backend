<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Loader;

use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\HttpKernel\KernelInterface;

class FixtureLoader
{
    private const PATH = '%s/Resources/fixtures/%s/fixture.yaml';

    private KernelInterface $kernel;

    private string $root;

    public function __construct(KernelInterface $kernel, string $root)
    {
        $this->kernel = $kernel;
        $this->root = $root;
    }

    /**
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
