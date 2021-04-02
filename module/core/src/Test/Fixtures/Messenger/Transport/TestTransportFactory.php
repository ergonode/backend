<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Fixtures\Messenger\Transport;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestTransportFactory implements TransportFactoryInterface
{
    private MessageBusInterface $messageBus;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        MessageBusInterface $messageBus,
        TokenStorageInterface $tokenStorage
    ) {
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new TestTransport(
            $serializer,
            $this->messageBus,
            $this->tokenStorage,
        );
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'test://');
    }
}
