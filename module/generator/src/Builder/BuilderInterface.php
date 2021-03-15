<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Generator\Builder;

use Nette\PhpGenerator\PhpFile;

interface BuilderInterface
{
    /**
     * @param array $properties
     */
    public function build(string $module, string $entity, array $properties = []): PhpFile;
}
