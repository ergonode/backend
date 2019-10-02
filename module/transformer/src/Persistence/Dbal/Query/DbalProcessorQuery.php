<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Query\ProcessorQueryInterface;

/**
 */
class DbalProcessorQuery implements ProcessorQueryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function findProcessorIdByTransformerId(TransformerId $transformerId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('importer.processor')
            ->where('transformer_id = :transformer')
            ->setParameter('transformer', $transformerId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProcessorId($item);
        }

        return $result;
    }
}
