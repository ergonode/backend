<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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

        return $files;
    }
}
