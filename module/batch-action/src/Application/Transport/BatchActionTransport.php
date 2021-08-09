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
use Doctrine\DBAL\Types\Types;

class BatchActionTransport implements TransportInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): iterable
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('batch_action_id, resource_id')
            ->from('batch_action_entry')
            ->setMaxResults(1)
            ->where($qb->expr()->isNull('processed_at'))
            ->execute()
            ->fetch();

        $result = [];
        if ($record) {
            $result[] = new Envelope(
                new ProcessBatchActionEntryCommand(
                    new BatchActionId($record['batch_action_id']),
                    new AggregateId($record['resource_id'])
                )
            );
        }

        return $result;
    }

    public function ack(Envelope $envelope): void
    {
        /** @var ProcessBatchActionEntryCommand $message */
        $message = $envelope->getMessage();
        $this->connection->update(
            'batch_action_entry',
            [
                'processed_at' => new \DateTime(),
            ],
            [
                'batch_action_id' => $message->getId()->getValue(),
                'resource_id' => $message->getResourceId()->getValue(),

            ],
            [
                'calculated_at' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function reject(Envelope $envelope): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        return $envelope;
    }
}
