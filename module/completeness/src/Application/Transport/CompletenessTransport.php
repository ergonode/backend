<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Envelope;
use Doctrine\DBAL\Connection;
use Ergonode\Completeness\Domain\Command\ProductCompletenessCalculateCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Types\Types;

class CompletenessTransport implements TransportInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): iterable
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('product_id')
            ->from('product_completeness')
            ->setMaxResults(1)
            ->where($qb->expr()->isNull('calculated_at'))
            ->execute()
            ->fetch();

        $result = [];
        if ($record) {
            $command = new ProductCompletenessCalculateCommand(new ProductId($record['product_id']));
            $result[] = new Envelope($command);
        }

        return $result;
    }

    public function ack(Envelope $envelope): void
    {
        /** @var ProductCompletenessCalculateCommand $message */
        $message = $envelope->getMessage();
        $this->connection->update(
            'product_completeness',
            [
                'calculated_at' => new \DateTime(),
            ],
            [
                'product_id' => $message->getProductId()->getValue(),
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
