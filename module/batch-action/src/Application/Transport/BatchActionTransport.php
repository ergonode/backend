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
use Ergonode\Core\Application\Security\User\CachedUser;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Core\Application\Messenger\Stamp\UserStamp;
use Psr\Log\LoggerInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionCommand;

class BatchActionTransport implements TransportInterface
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

    /**
     * @return Envelope[]
     */
    public function get(): iterable
    {
        $this->connection->beginTransaction();

        try {
            $envelope = $this->getBatchActionEntryMessage();
            if (!$envelope) {
                // close mass action if there is no entry to process
                $envelope = $this->getBatchActionMessage();
            }

            if ($envelope) {
                return [$envelope];
            }

            // Transaction is close if there is no messages to process,
            // in other case transaction is closing in ack or reject method
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            $this->logger->error($exception);
        }

        return [];
    }

    public function ack(Envelope $envelope): void
    {
        $message = $envelope->getMessage();

        try {
            if ($message instanceof ProcessBatchActionEntryCommand) {
                /** @var HandledStamp $stamp */
                $stamp = $envelope->last(HandledStamp::class);

                $this->repository->markEntry($message->getId(), $message->getResourceId(), $stamp->getResult());
            }

            if ($message instanceof ProcessBatchActionCommand) {
                $this->repository->endBatchAction($message->getId());
            }

            // Close transaction to release table lock after ack processed message
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            $this->logger->error($exception);
        }
    }

    public function reject(Envelope $envelope): void
    {
        // Close transaction to release table lock after reject processed message
        $this->connection->rollBack();
    }

    public function send(Envelope $envelope): Envelope
    {
        return $envelope;
    }

    private function getBatchActionEntryMessage(): ?Envelope
    {
        $record = $this->connection->executeQuery(
            'SELECT ba.id, bae.resource_id, ba.created_by
                 FROM batch_action_entry bae 
                 JOIN batch_action ba ON ba.id = bae.batch_action_id 
                 WHERE bae.processed_at is NULL LIMIT 1 FOR UPDATE SKIP LOCKED'
        )->fetchAssociative();

        if (!empty($record)) {
            $envelope = new Envelope(
                new ProcessBatchActionEntryCommand(
                    new BatchActionId($record['id']),
                    new AggregateId($record['resource_id'])
                )
            );

            if (!empty($record['created_by'])) {
                $envelope = $this->addUSerStamp($envelope, new UserId($record['created_by']));
            }

            return $envelope;
        }

        return null;
    }

    private function getBatchActionMessage(): ?Envelope
    {
        $record = $this->connection->executeQuery(
            'SELECT id, created_by
                 FROM batch_action 
                 WHERE processed_at IS NULL LIMIT 1 FOR UPDATE SKIP LOCKED'
        )
            ->fetchAssociative();

        if (!empty($record)) {
            $envelope = new Envelope(new ProcessBatchActionCommand(new BatchActionId($record['id'])));
            if (!empty($record['created_by'])) {
                $envelope = $this->addUSerStamp($envelope, new UserId($record['created_by']));
            }

            return $envelope;
        }

        return null;
    }

    private function addUserStamp(Envelope $envelope, UserId $userId): Envelope
    {
        $user = $this->userRepository->load($userId);
        if ($user) {
            $envelope = $envelope->with(
                new UserStamp(
                    CachedUser::createFromUser($user),
                ),
            );
        }

        return $envelope;
    }
}
