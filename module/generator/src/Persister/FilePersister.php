<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Persister;

use Ergonode\Generator\Printer\ErgoPrinter;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

/**
 */
class FilePersister
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param PhpFile $file
     * @param string  $module
     */
    public function persist(PhpFile $file, string $module): void
    {
        /** @var PhpNamespace[] $namespaces */
        $namespaces = $file->getNamespaces();
        $namespace = reset($namespaces);
        /** @var ClassType[] $classes */
        $classes = $namespace->getClasses();
        $class = reset($classes);
        $path = str_replace(sprintf('Ergonode\\%s', ucfirst($module)), '', $namespace->getName());
        $path = str_replace('\\', '/', $path);


        $directory = sprintf('%s/module/%s/src/%s', $this->directory, $module, $path);
        $directory = str_replace('//', '/', $directory);

        if (!file_exists($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        $filename = sprintf('%s/%s.php', $directory, $class->getName());


        $printer = new ErgoPrinter();

        file_put_contents($filename, $printer->printFile($file));
    }
}
