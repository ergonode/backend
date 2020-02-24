<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Factory;

use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Resolver\TemplateElementTypeResolver;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class TemplateElementFactory
{
    /**
     * @var TemplateElementTypeResolver
     */
    private TemplateElementTypeResolver $resolver;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param TemplateElementTypeResolver $resolver
     * @param SerializerInterface         $serializer
     */
    public function __construct(TemplateElementTypeResolver $resolver, SerializerInterface $serializer)
    {
        $this->resolver = $resolver;
        $this->serializer = $serializer;
    }

    /**
     * @param Position $position
     * @param Size     $size
     * @param string   $type
     * @param array    $properties
     *
     * @return TemplateElement
     */
    public function create(Position $position, Size $size, string $type, array $properties = []): TemplateElement
    {
        $properties['variant'] = $this->resolver->resolve($type);

        $property = $this->serializer->deserialize(
            json_encode($properties),
            TemplateElementPropertyInterface::class,
            'json'
        );

        return new TemplateElement(
            $position,
            $size,
            $type,
            $property
        );
    }
}
