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

class TransformerFieldAddedEvent implements AggregateEventInterface
{
    private TransformerId $id;

    private string $field;

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
