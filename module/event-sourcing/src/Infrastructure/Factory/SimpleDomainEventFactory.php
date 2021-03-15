<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Factory;

use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\SharedKernel\Domain\AggregateId;

class SimpleDomainEventFactory implements DomainEventFactoryInterface
{
    /**
     * @var SerializerInterface;
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AggregateId $id, array $records): array
    {
        $result = [];
        foreach ($records as $record) {
            $result[] = $this->createElement($id, $record);
        }

        return $result;
    }

    /**
     * @param array $record
     */
    private function createElement(AggregateId $id, array $record): DomainEventEnvelope
    {
        return new DomainEventEnvelope(
            $id,
            $record['sequence'],
            $this->getEvent($record['event'], $record['payload']),
            new \DateTime($record['recorded_at']),
        );
    }

    private function getEvent(string $class, string $data): AggregateEventInterface
    {
        return $this->serializer->deserialize($data, $class);
    }
}
