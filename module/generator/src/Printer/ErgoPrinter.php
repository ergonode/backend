<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Generator\Printer;

use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;
use Nette\Utils\Strings;

class ErgoPrinter extends Printer
{
    /**
     * @var string
     */
    protected $indentation = '    ';

    /**
     * @var int
     */
    protected $linesBetweenMethods = 1;

    public function printFile(PhpFile $file): string
    {
        $namespaces = [];
        foreach ($file->getNamespaces() as $namespace) {
            $namespaces[] = $this->printNamespace($namespace);
        }

        $s = "<?php\n";
        $s .= ($file->getComment() ? "\n".Helpers::formatDocComment($file->getComment()."\n") : '');
        $s .= "\n";
        $s .= ($file->getStrictTypes() ? "declare(strict_types = 1);\n\n" : '');
        $s .= implode("\n\n", $namespaces);

        return Strings::normalize($s)."\n";
    }

    /**
     * @param $var
     */
    protected function dump($var): string
    {
        return str_replace("\t", $this->indentation, Helpers::dump($var));
    }
}
