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
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Test transport that immediately dispatches message for handling.
 * The message is being serialized and deserialized as a message via regular transport would.
 */
class TestTransport implements TransportInterface
{
    private SerializerInterface $serializer;
    private MessageBusInterface $messageBus;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        SerializerInterface $serializer,
        MessageBusInterface $messageBus,
        TokenStorageInterface $tokenStorage
    ) {
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): iterable
    {
        throw new InvalidArgumentException('You cannot receive messages from the Messenger TestTransport.');
    }

    public function ack(Envelope $envelope): void
    {
        throw new InvalidArgumentException('You cannot call ack() on the Messenger TestTransport.');
    }

    public function reject(Envelope $envelope): void
    {
        throw new InvalidArgumentException('You cannot call reject() on the Messenger TestTransport.');
    }

    public function send(Envelope $envelope): Envelope
    {
        $sentMessage = $this->serializer->encode($envelope);
        $receivedMessage = $this->serializer->decode($sentMessage);

        $token = $this->tokenStorage->getToken();
        $this->tokenStorage->setToken();

        $result = $this->dispatchMessage($receivedMessage);

        $this->tokenStorage->setToken($token);

        return $result;
    }

    private function dispatchMessage(Envelope $envelope): Envelope
    {
        /** @var SentStamp|null $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);
        $alias = null === $sentStamp ? 'test' : ($sentStamp->getSenderAlias() ?? $sentStamp->getSenderClass());

        $envelope = $envelope->with(new ReceivedStamp($alias));

        return $this->messageBus->dispatch($envelope);
    }
}
