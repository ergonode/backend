<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransformerCreatedEvent implements DomainEventInterface
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $key;

    /**
     * @param TransformerId $id
     * @param string        $name
     * @param string        $key
     */
    public function __construct(TransformerId $id, string $name, string $key)
    {
        $this->id = $id;
        $this->name = $name;
        $this->key = $key;
    }

    /**
     * @return TransformerId
     */
    public function getAggregateId(): TransformerId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
