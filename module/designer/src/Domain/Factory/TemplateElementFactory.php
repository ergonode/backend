<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Factory;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Resolver\TemplateElementTypeResolver;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class TemplateElementFactory
{
    private TemplateElementTypeResolver $resolver;

    private SerializerInterface $serializer;

    public function __construct(TemplateElementTypeResolver $resolver, SerializerInterface $serializer)
    {
        $this->resolver = $resolver;
        $this->serializer = $serializer;
    }

    /**
     * @param array $properties
     */
    public function create(
        Position $position,
        Size $size,
        string $type,
        array $properties = []
    ): TemplateElementInterface {
        $properties['position']['x'] = $position->getX();
        $properties['position']['y'] = $position->getY();
        $properties['size']['width'] = $size->getWidth();
        $properties['size']['height'] = $size->getHeight();
        $properties['type'] = $this->resolver->resolve($type);

        return $this->serializer->deserialize(
            json_encode($properties),
            TemplateElementInterface::class
        );
    }
}
