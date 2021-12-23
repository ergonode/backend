<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Transport;

use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Envelope;
use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Command\Product\SetProductDefaultWorkflowStatusCommand;

class StatusTransport implements TransportInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): iterable
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('p.id, lt.code')
            ->from('product', 'p')
            ->join('p', 'language_tree', 'lt', 'true')
            ->leftJoin('p', 'product_workflow_status', 'pws', 'pws.product_id = p.id AND pws.language = lt.code')
            ->setMaxResults(1)
            ->where($qb->expr()->isNull('product_id'))
            ->execute()
            ->fetch();

        $result = [];

        if ($record) {
            $productId = new ProductId($record['id']);
            $language = new Language($record['code']);
            $command = new SetProductDefaultWorkflowStatusCommand($productId, $language);
            $result[] = new Envelope($command);
        }

        return $result;
    }

    public function ack(Envelope $envelope): void
    {
    }

    public function reject(Envelope $envelope): void
    {
    }

    public function send(Envelope $envelope): Envelope
    {
        return $envelope;
    }
}
