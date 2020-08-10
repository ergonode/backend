<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Query\ProductDashboardQueryInterface;
use Ergonode\Product\Application\Provider\ProductTypeProvider;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class DbalProductDashboardQuery implements ProductDashboardQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ProductTypeProvider
     */
    private ProductTypeProvider $provider;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param Connection          $connection
     * @param ProductTypeProvider $provider
     * @param TranslatorInterface $translator
     */
    public function __construct(Connection $connection, ProductTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getProductCount(Language  $language): array
    {
        $result = [];
        $types = $this->provider->provide();
        foreach ($types as $type) {
            $result[$type] = [
                'label' => $this->translator->trans($type, [], 'product', $language->getCode()),
                'count' => 0,
                'type' => $type
            ];
        }

        $qb = $this->connection->createQueryBuilder();
        $records = $qb->select('type, count(*)')
            ->from(self::PRODUCT_TABLE)
            ->groupBy('type')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        foreach ($records as $type => $count) {
            $result[$type]['count'] = $count;
        }

        return array_values($result);
    }
}
