<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalTemplateQuery implements TemplateQueryInterface
{
    private const TABLE = 'designer.template';
    private const ELEMENTS_TABLE = 'designer.template_element';
    private const SECTIONS_TABLE = 'designer.template_section';
    private const FIELDS = [
        't.id',
        't.name',
        't.image_id',
        't.template_group_id AS group_id',
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
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $this->getQuery()->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param TemplateId $id
     * @param Language   $language
     *
     * @return array
     */
    public function getTemplate(TemplateId $id, Language $language): array
    {
        $qb = $this->getQuery();
        $result = $qb
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            $result['elements'] = $this->getTemplateElements($id, $language);
            $result['sections'] = $this->getTemplateSections($id);

            return $result;
        }

        return [];
    }

    /**
     * @param string $name
     *
     * @return TemplateId|null
     */
    public function findIdByName(string $name): ?TemplateId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);
        if ($result) {
            return new TemplateId($result);
        }

        return null;
    }

    /**
     * @param TemplateId $id
     *
     * @param Language $language
     *
     * @return array
     */
    private function getTemplateElements(TemplateId $id, Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('te.element_id AS id, COALESCE(el.value, e.code) as label, te.x, te.y, te.width, te.height, te.required, et.type, et.min_width, et.min_height, et.max_width, et.max_height')
            ->from(self::ELEMENTS_TABLE, 'te')
            ->join('te', 'designer.element', 'e', 'e.id = te.element_id')
            ->leftJoin('e', 'designer.element_type', 'et', 'et.type = e.type')
            ->leftJoin('te', 'designer.element_label', 'el', 'el.element_id = te.element_id AND el.language = :language')
            ->where($qb->expr()->eq('te.template_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':language', $language->getCode())
            ->orderBy('te.x', 'ASC')
            ->addOrderBy('te.y', 'ASC')
            ->execute()
            ->fetchAll();
    }

    /**
     * @param TemplateId $id
     *
     * @return array
     */
    private function getTemplateSections(TemplateId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('row, title')
            ->from(self::SECTIONS_TABLE)
            ->where($qb->expr()->eq('template_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->orderBy('row', 'ASC')
            ->execute()
            ->fetchAll();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 't');
    }
}
