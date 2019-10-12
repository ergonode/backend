<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Builder;

use Nette\PhpGenerator\PhpFile;

/**
 */
class FileBuilder
{
    /**
     * @return PhpFile
     */
    public function build(): PhpFile
    {
        $file = new PhpFile();
        $file->addComment('This file is auto-generated.');
        $file->setStrictTypes();

        return $file;
    }
}
