<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use JMS\Serializer\Annotation as JMS;

class TransformerFieldAddedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @JMS\Type("string")
     */
    private string $field;

    /**
     * @JMS\Type("Ergonode\Importer\Infrastructure\Converter\ConverterInterface")
     */
    private ConverterInterface $converter;

    public function __construct(TransformerId $id, string $field, ConverterInterface $converter)
    {
        $this->id = $id;
        $this->field = $field;
        $this->converter = $converter;
    }

    public function getAggregateId(): TransformerId
    {
        return $this->id;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getConverter(): ConverterInterface
    {
        return $this->converter;
    }
}
