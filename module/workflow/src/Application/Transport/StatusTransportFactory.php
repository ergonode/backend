<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Doctrine\DBAL\Connection;

class StatusTransportFactory implements TransportFactoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new StatusTransport($this->connection);
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'status://');
    }
}
