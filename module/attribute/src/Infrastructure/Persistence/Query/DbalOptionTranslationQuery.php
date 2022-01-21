<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Query\OptionTranslationQueryInterface;

class DbalOptionTranslationQuery implements OptionTranslationQueryInterface
{
    private const TABLE_OPTION = 'attribute_option';
    private const TABLE_OPTIONS = 'attribute_options';
    private const TABLE_VALUES = 'value_translation';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getLabels(AttributeId $attributeId, Language $language): array
    {
        $result = [];

        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('o.id, o.key, t.value')
            ->from(self::TABLE_OPTION, 'o')
            ->leftJoin('o', self::TABLE_VALUES, 't', 't.value_id = o.value_id AND language = :language')
            ->join('o', self::TABLE_OPTIONS, 'os', 'os.option_id = o.id')
            ->where($qb->expr()->eq('os.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->setParameter(':language', $language->getCode())
            ->orderBy('os.index')
            ->execute()
            ->fetchAllAssociative();

        foreach ($records as $record) {
            $result[$record['id']] = [
                'code' => $record['key'],
                'label' => $record['value'],
            ];
        }

        return $result;
    }
}
