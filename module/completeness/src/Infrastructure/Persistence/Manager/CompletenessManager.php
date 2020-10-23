<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Manager;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Core\Domain\ValueObject\Language;

class CompletenessManager
{
    private const TABLE = 'product_completeness';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(ProductId $productId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'product_id' => $productId->getValue(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function add(
        ProductId $productId,
        TemplateId $templateId,
        AttributeId $attributeId,
        Language $language,
        bool $required,
        bool $filled
    ): void {
        $this->connection->executeQuery(
            'INSERT INTO product_completeness (attribute_id, product_id, template_id, language, required, filled) 
                       VALUES (?, ?, ?, ? ,? , ?) ON CONFLICT DO NOTHING',
            [
                $attributeId->getValue(),
                $productId->getValue(),
                $templateId->getValue(),
                $language->getCode(),
                $required,
                $filled,
            ],
            [
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_BOOL,
                \PDO::PARAM_BOOL,
            ]
        );
    }
}
