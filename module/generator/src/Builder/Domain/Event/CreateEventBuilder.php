<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Generator\Builder\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\Generator\Builder\BuilderInterface;
use Ergonode\Generator\Builder\FileBuilder;
use Ergonode\Generator\Builder\MethodBuilder;
use Ergonode\Generator\Builder\PropertyBuilder;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;

class CreateEventBuilder implements BuilderInterface
{
    private FileBuilder $builder;

    private MethodBuilder $methodBuilder;

    private PropertyBuilder $propertyBuilder;

    public function __construct(FileBuilder $builder, MethodBuilder $methodBuilder, PropertyBuilder $propertyBuilder)
    {
        $this->builder = $builder;
        $this->methodBuilder = $methodBuilder;
        $this->propertyBuilder = $propertyBuilder;
    }

    /**
     * @param array $properties
     */
    public function build(string $module, string $entity, array $properties = []): PhpFile
    {
        $file = $this->builder->build();

        $className = sprintf('%sCreatedEvent', ucfirst($entity));
        $entityIdClass = sprintf('Ergonode\%s\Domain\Entity\%sId', ucfirst($module), $entity);
        $namespace = sprintf('Ergonode\%s\Domain\Event', ucfirst($module));
        $properties = array_merge(['id' => $entityIdClass], $properties);

        $phpNamespace = $file->addNamespace($namespace);
        $phpNamespace->addUse('\JMS\Serializer\Annotation', 'JMS');

        $phpClass = $phpNamespace->addClass($className);
        $phpClass->addImplement(AggregateEventInterface::class);
        $phpClass->addComment('Autogenerated class');

        $phpClass->addMember($this->buildConstructor($properties));

        foreach ($properties as $name => $type) {
            $property = $this->propertyBuilder->build($name, $type);
            $property->addComment('');
            $property->addComment(sprintf('@JMS\Type("%s")', $type));
            $phpClass->addMember($property);
            $phpClass->addMember($this->buildGetter($name, $type));
        }

        return $file;
    }

    /**
     * @param array $properties
     */
    private function buildConstructor(array $properties = []): Method
    {
        $method = $this->methodBuilder->build('__construct', $properties);
        foreach (array_keys($properties) as $name) {
            $method->addBody(sprintf('$this->%s = $%s;', $name, $name));
        }

        return $method;
    }

    private function buildGetter(string $name, string $returnType): Method
    {
        $method = $this->methodBuilder->build(sprintf('get%s', ucfirst($name)), [], $returnType);
        $method->addBody(sprintf('return $this->%s;', $name));

        return $method;
    }
}
