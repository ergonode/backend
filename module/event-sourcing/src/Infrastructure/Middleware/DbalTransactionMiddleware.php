<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Middleware;

use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;

class DbalTransactionMiddleware implements MiddlewareInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
     */
    public function handle(
        Envelope $envelope,
        StackInterface $stack
    ): Envelope {
        $this->connection->beginTransaction();
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
            $this->connection->commit();

            return $envelope;
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            if ($exception instanceof HandlerFailedException) {
                throw new HandlerFailedException(
                    $exception->getEnvelope()
                        ->withoutAll(HandledStamp::class),
                    $exception->getNestedExceptions()
                );
            }

            throw $exception;
        }
    }
}
