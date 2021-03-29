<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\PropertyInfo\Extractor;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class ReflectionExtractor implements PropertyTypeExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property, array $context = []): ?array
    {
        try {
            $reflectionProperty = new \ReflectionProperty($class, $property);
        } catch (\ReflectionException $exception) {
            return null;
        }
        $type = $reflectionProperty->getType();
        if (!$type) {
            return null;
        }

        return $this->extractFromReflectionType($type, $reflectionProperty->getDeclaringClass());
    }

    private function extractFromReflectionType(
        \ReflectionType $reflectionType,
        \ReflectionClass $declaringClass
    ): ?array {
        $types = [];
        $nullable = $reflectionType->allowsNull();

        $reflectionTypes = $reflectionType instanceof \ReflectionUnionType ?
            $reflectionType->getTypes() :
            [$reflectionType];

        foreach ($reflectionTypes as $type) {
            $phpTypeOrClass = $reflectionType instanceof \ReflectionNamedType ?
                $reflectionType->getName() :
                (string) $type;

            // skipping array type entirely
            if (Type::BUILTIN_TYPE_ARRAY === $phpTypeOrClass) {
                return null;
            }

            if (in_array($phpTypeOrClass, ['null', 'mixed', Type::BUILTIN_TYPE_ARRAY])) {
                continue;
            }

            if ('void' === $phpTypeOrClass) {
                $types[] = new Type(Type::BUILTIN_TYPE_NULL, $nullable);
            } elseif ($type->isBuiltin()) {
                $types[] = new Type($phpTypeOrClass, $nullable);
            } else {
                $types[] = new Type(
                    Type::BUILTIN_TYPE_OBJECT,
                    $nullable,
                    $this->resolveTypeName($phpTypeOrClass, $declaringClass),
                );
            }
        }

        return $types;
    }

    private function resolveTypeName(string $name, \ReflectionClass $declaringClass): string
    {
        if ('self' === $lcName = strtolower($name)) {
            return $declaringClass->name;
        }
        if ('parent' === $lcName && $parent = $declaringClass->getParentClass()) {
            return $parent->name;
        }

        return $name;
    }
}
