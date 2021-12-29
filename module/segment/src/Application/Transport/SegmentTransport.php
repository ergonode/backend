<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Envelope;
use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Types\Types;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Command\CalculateSegmentProductCommand;

class SegmentTransport implements TransportInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): iterable
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('segment_id, product_id')
            ->from('segment_product')
            ->setMaxResults(1)
            ->where($qb->expr()->isNull('calculated_at'))
            ->execute()
            ->fetch();

        $result = [];
        if ($record) {
            $command = new CalculateSegmentProductCommand(
                new SegmentId($record['segment_id']),
                new ProductId($record['product_id'])
            );
            $result[] = new Envelope($command);
        }

        return $result;
    }

    public function ack(Envelope $envelope): void
    {
        /** @var CalculateSegmentProductCommand $message */
        $message = $envelope->getMessage();
        $this->connection->update(
            'segment_product',
            [
                'calculated_at' => new \DateTime(),
            ],
            [
                'product_id' => $message->getProductId()->getValue(),
                'segment_id' => $message->getSegmentId()->getValue(),
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
