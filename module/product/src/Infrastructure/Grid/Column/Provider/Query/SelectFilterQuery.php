<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class SelectFilterQuery implements FilterQueryInterface
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
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function support(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof SelectAttribute;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language|null     $language
     * @param string|null       $value
     *
     * @return QueryBuilder
     */
    public function query(AbstractAttribute $attribute, ?Language $language = null, ?string $value = null): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('pv.product_id AS id');
        $qb->from('value_translation', 'vt');
        $qb->join('vt', 'product_value', 'pv', 'pv.value_id = vt.value_id');

        if ($language) {
            $qb->andWhere($qb->expr()->eq('vt.language', $language->getCode()));
        } else {
            $qb->andWhere($qb->expr()->isNull('vt.language'));
        }

        $qb->andWhere($qb->expr()->eq('pv.attribute_id', $qb->createNamedParameter($attribute->getId()->getValue())));
        if($value) {
            $qb->andWhere(\sprintf('vt.value ILIKE %s', $qb->createNamedParameter(\sprintf('%%%s%%', $this->escape($value)))));
        }

        return $qb;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function escape(string $value): string
    {
        $replace = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}
