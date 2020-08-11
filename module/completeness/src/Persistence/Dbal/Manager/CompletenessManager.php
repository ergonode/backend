<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Persistence\Dbal\Manager;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class CompletenessManager
{
    private const TABLE = 'product_completeness';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductId $productId
     *
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
     * @param ProductId   $productId
     * @param TemplateId  $templateId
     * @param AttributeId $attributeId
     * @param Language    $language
     * @param bool        $required
     * @param bool        $filled
     *
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
        $this->connection->insert(
            self::TABLE,
            [
                'attribute_id' => $attributeId->getValue(),
                'product_id' => $productId->getValue(),
                'template_id' => $templateId->getValue(),
                'language' => $language->getCode(),
                'required' => $required,
                'filled' => $filled,
            ],
            [
                'required' => \PDO::PARAM_BOOL,
                'filled' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
