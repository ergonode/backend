<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Fixtures\Messenger\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Test transport that immediately dispatches message for handling.
 * The message is being serialized and deserialized as a message via regular transport would.
 */
class TestTransport implements TransportInterface
{
    private SerializerInterface $serializer;
    private MessageBusInterface $messageBus;

    public function __construct(SerializerInterface $serializer, MessageBusInterface $messageBus)
    {
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
    }

    public function get(): iterable
    {
        throw new InvalidArgumentException('You cannot receive messages from the Messenger SyncTransport.');
    }

    public function ack(Envelope $envelope): void
    {
        throw new InvalidArgumentException('You cannot call ack() on the Messenger SyncTransport.');
    }

    public function reject(Envelope $envelope): void
    {
        throw new InvalidArgumentException('You cannot call reject() on the Messenger SyncTransport.');
    }

    public function send(Envelope $envelope): Envelope
    {
        $sentMessage = $this->serializer->encode($envelope);
        $receivedMessage = $this->serializer->decode($sentMessage);

        return $this->messageBus->dispatch($receivedMessage);
    }
}
