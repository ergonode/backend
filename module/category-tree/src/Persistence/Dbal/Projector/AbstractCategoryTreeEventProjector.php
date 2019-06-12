<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Category\Domain\Entity\CategoryId;

/**
 */
abstract class AbstractCategoryTreeEventProjector implements DomainEventProjectorInterface
{
    protected const SEQUENCE_KEY_LENGTH = 5;
    protected const TABLE = 'tree';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return string|null
     */
    protected function getSequence(CategoryId $categoryId): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('sequence')
            ->from('category')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $categoryId->getValue())
            ->execute()
            ->fetchColumn();

        if ($result) {
            return  str_pad((string) $result, self::SEQUENCE_KEY_LENGTH, '0', STR_PAD_LEFT);
        }

        return null;
    }
}
