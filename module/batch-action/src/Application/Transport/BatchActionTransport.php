<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Envelope;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;

class BatchActionTransport implements TransportInterface
{
    private Connection $connection;

    private BatchActionRepositoryInterface $repository;

    public function __construct(Connection $connection, BatchActionRepositoryInterface $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
    }

    public function get(): iterable
    {
        $this->connection->beginTransaction();
        $result = [];
        $record = $this->connection->executeQuery(
            'SELECT batch_action_id, resource_id 
                 FROM batch_action_entry 
                 WHERE processed_at is NULL LIMIT 1 FOR UPDATE SKIP LOCKED'
        )->fetchAssociative();

        if (!empty($record)) {
            echo ($record['resource_id']).PHP_EOL;
            $result[] = new Envelope(
                new ProcessBatchActionEntryCommand(
                    new BatchActionId($record['batch_action_id']),
                    new AggregateId($record['resource_id'])
                )
            );
        } else {
            $this->connection->commit();
        }

        return $result;
    }

    public function ack(Envelope $envelope): void
    {
        /** @var ProcessBatchActionEntryCommand $message */
        $message = $envelope->getMessage();
        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);

        $this->repository->markEntry($message->getId(), $message->getResourceId(), $stamp->getResult());
        $this->connection->commit();
    }

    public function reject(Envelope $envelope): void
    {
        $this->connection->commit();
    }

    public function send(Envelope $envelope): Envelope
    {
        return $envelope;
    }
}
