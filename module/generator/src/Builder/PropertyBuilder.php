<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Builder;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Property;

/**
 */
class PropertyBuilder
{
    /**
     * @param string      $propertyName
     * @param string|null $type
     * @param bool        $nullable
     *
     * @return Property
     */
    public function build(string $propertyName, string $type = null, bool $nullable = false): Property
    {
        $property = new Property($propertyName);
        $property->setVisibility(ClassType::VISIBILITY_PRIVATE);

        if ($type) {
            $path = explode('\\', $type);
            $baseType = array_pop($path);

            $property
                ->setComment(sprintf('@var %s $%s', $baseType, $propertyName));
        } else {
            $property
                ->setComment(sprintf('@var mixed $%s', $propertyName));
        }

        return $property;
    }
}