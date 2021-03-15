<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Generator\Builder;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class MethodBuilder
{
    /**
     * @param array $properties
     */
    public function build(
        string $methodName,
        array $properties = [],
        string $returnType = null,
        bool $nullable = false
    ): Method {
        $method = new Method($methodName);
        $method->setVisibility(ClassType::VISIBILITY_PUBLIC);

        foreach ($properties as $name => $type) {
            if ($type) {
                $path = explode('\\', $type);
                $baseType = array_pop($path);

                $method
                    ->addComment(sprintf('@param %s $%s', $baseType, $name))
                    ->addParameter($name)
                    ->setTypeHint($type);
            } else {
                $method
                    ->addComment(sprintf('@param mixed $%s', $name))
                    ->addParameter($name);
            }
        }

        if (null !== $returnType) {
            if ('void' !== $returnType) {
                $path = explode('\\', $returnType);
                $baseReturnType = array_pop($path);
                $method->addComment('');
                $method->addComment(sprintf('@return %s', $baseReturnType));
                if ($nullable) {
                    $method->setReturnNullable();
                }
            }
            $method->setReturnType($returnType);
        }

        return $method;
    }
}
