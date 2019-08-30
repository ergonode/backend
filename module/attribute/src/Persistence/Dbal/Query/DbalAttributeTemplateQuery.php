<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeTemplateQueryInterface;

/**
 * Class DbalAttributeTemplateQuery
 */
class DbalAttributeTemplateQuery implements AttributeTemplateQueryInterface
{
    private const TEMPLATE_TABLE = 'editor.template';
    private const TEMPLATE_ATTRIBUTE_TABLE = 'editor.template_element';
    private const FIELDS = [
        't.id',
        't.name',
    ];

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
     * @param AttributeId $id
     *
     * @return array
     */
    public function getDesignTemplatesByAttributeId(AttributeId $id): array
    {
        $qb = $this->getQuery();

        return $qb
            ->andWhere($qb->expr()->eq('at.element_id', ':attributeId'))
            ->setParameter(':attributeId', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TEMPLATE_TABLE, 't')
            ->join('t', self::TEMPLATE_ATTRIBUTE_TABLE, 'at', 'at.template_id = t.id');
    }
}
