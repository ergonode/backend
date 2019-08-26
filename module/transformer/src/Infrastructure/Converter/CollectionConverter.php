<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CollectionConverter implements ConverterInterface
{
    public const TYPE = 'collection';

    /**
     * @var array
     *
     * @JMS\Type("array<string>")
     */
    private $fields;

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param array  $line
     * @param string $field
     *
     * @return ValueInterface
     */
    public function map(array $line, string $field): ValueInterface
    {
        $collection = [];
        foreach ($this->fields as $collectionField) {
            if (isset($line[$collectionField])) {
                $collection[] = $line[$collectionField];
            }
        }

        return new StringCollectionValue($collection);
    }
}
