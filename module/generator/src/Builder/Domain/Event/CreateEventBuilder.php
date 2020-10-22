<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Builder\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Generator\Builder\BuilderInterface;
use Ergonode\Generator\Builder\FileBuilder;
use Ergonode\Generator\Builder\MethodBuilder;
use Ergonode\Generator\Builder\PropertyBuilder;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;

class CreateEventBuilder implements BuilderInterface
{
    /**
     * @var FileBuilder
     */
    private FileBuilder $builder;

    /**
     * @var MethodBuilder
     */
    private MethodBuilder $methodBuilder;

    /**
     * @var PropertyBuilder
     */
    private PropertyBuilder $propertyBuilder;

    /**
     * @param FileBuilder     $builder
     * @param MethodBuilder   $methodBuilder
     * @param PropertyBuilder $propertyBuilder
     */
    public function __construct(FileBuilder $builder, MethodBuilder $methodBuilder, PropertyBuilder $propertyBuilder)
    {
        $this->builder = $builder;
        $this->methodBuilder = $methodBuilder;
        $this->propertyBuilder = $propertyBuilder;
    }

    /**
     * @param string $module
     * @param string $entity
     * @param array  $properties
     *
     * @return PhpFile
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
        $phpClass->addImplement(DomainEventInterface::class);
        $phpClass->addComment('Autogenerated class');

        $phpClass->addMember($this->buildConstructor($entity, $properties));

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
     * @param string $entity
     * @param array  $properties
     *
     * @return Method
     */
    private function buildConstructor(string $entity, array $properties = []): Method
    {
        $method = $this->methodBuilder->build('__construct', $properties);
        foreach ($properties as $name => $type) {
            $method->addBody(sprintf('$this->%s = $%s;', $name, $name));
        }

        return $method;
    }

    /**
     * @param string $name
     * @param string $returnType
     *
     * @return Method
     */
    private function buildGetter(string $name, string $returnType): Method
    {
        $method = $this->methodBuilder->build(sprintf('get%s', ucfirst($name)), [], $returnType);
        $method->addBody(sprintf('return $this->%s;', $name));

        return $method;
    }
}
