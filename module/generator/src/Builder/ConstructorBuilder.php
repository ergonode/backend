<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);


namespace Ergonode\Generator\Builder;

use Nette\PhpGenerator\Method;

/**
 */
class ConstructorBuilder
{
    /**
     * @var MethodBuilder
     */
    private $methodBuilder;

    /**
     * @param string $entity
     * @param array  $properties
     *
     * @return Method
     */
    public function build(string $entity, array $properties = []): Method
    {

    }
}
