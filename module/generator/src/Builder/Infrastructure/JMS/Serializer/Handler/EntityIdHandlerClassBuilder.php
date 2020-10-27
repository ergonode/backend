<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Generator\Builder\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Generator\Builder\BuilderInterface;
use Ergonode\Generator\Builder\FileBuilder;
use Ergonode\Generator\Builder\MethodBuilder;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Nette\PhpGenerator\PhpFile;

class EntityIdHandlerClassBuilder implements BuilderInterface
{
    private FileBuilder $builder;

    private MethodBuilder $methodBuilder;

    public function __construct(FileBuilder $builder, MethodBuilder $methodBuilder)
    {
        $this->builder = $builder;
        $this->methodBuilder = $methodBuilder;
    }

    /**
     * @param array $properties
     */
    public function build(string $module, string $entity, array $properties = []): PhpFile
    {
        $file = $this->builder->build();
        $className = sprintf('%sIdHandler', $entity);

        $namespace = sprintf('Ergonode\%s\Infrastructure\JMS\Serializer\Handler', ucfirst($module));
        $entityIdClass = sprintf('Ergonode\%s\Domain\Entity\%sId', ucfirst($module), $entity);

        $phpNamespace = $file->addNamespace($namespace);
        $phpNamespace->addUse($entityIdClass);
        $phpNamespace->addUse(SubscribingHandlerInterface::class);
        $phpNamespace->addUse(GraphNavigatorInterface::class);
        $phpNamespace->addUse(SerializationVisitorInterface::class);
        $phpNamespace->addUse(DeserializationVisitorInterface::class);
        $phpNamespace->addUse(Context::class);


        $class = $phpNamespace->addClass($className);
        $class->addImplement(SubscribingHandlerInterface::class);
        $class->addComment('Autogenerated class');

        $method = $this->methodBuilder->build('getSubscribingMethods', [], 'array');
        $method
            ->setStatic()
            ->setBody(sprintf('$methods = [];
$formats = [\'json\', \'xml\', \'yml\'];

foreach ($formats as $format) {
    $methods[] = [
        \'direction\' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
        \'type\' => %sId::class,
        \'format\' => $format,
        \'method\' => \'serialize\',
    ];

    $methods[] = [
        \'direction\' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
        \'type\' => %sId::class,
        \'format\' => $format,
        \'method\' => \'deserialize\',
    ];
}

return $methods;', $entity, $entity));

        $class->addMember($method);

        $method = $this->methodBuilder->build(
            'serialize',
            [
                'visitor' => SerializationVisitorInterface::class,
                'id' => $entityIdClass,
                'type' => 'array',
                'context' => Context::class,
            ],
            'string'
        );
        $method->addBody('return $id->getValue();');
        $class->addMember($method);


        $method = $this->methodBuilder->build(
            'deserialize',
            [
                'visitor' => DeserializationVisitorInterface::class,
                'data' => null,
                'type' => 'array',
                'context' => Context::class,
            ],
            $entityIdClass
        );
        $method->addBody(sprintf('return new %sId($data);', $entity));
        $class->addMember($method);

        return $file;
    }
}
