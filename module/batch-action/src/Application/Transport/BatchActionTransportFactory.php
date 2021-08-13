<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

class BatchActionTransportFactory implements TransportFactoryInterface
{
    private Connection $connection;

    private BatchActionRepositoryInterface $repository;

    private UserRepositoryInterface $userRepository;

    private LoggerInterface $logger;

    public function __construct(
        Connection $connection,
        BatchActionRepositoryInterface $repository,
        UserRepositoryInterface $userRepository,
        LoggerInterface $logger
    ) {
        $this->connection = $connection;
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new BatchActionTransport(
            $this->connection,
            $this->repository,
            $this->userRepository,
            $this->logger,
        );
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'batch-action://');
    }
}
