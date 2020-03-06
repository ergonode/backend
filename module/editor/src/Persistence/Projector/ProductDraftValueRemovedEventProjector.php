<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Editor\Domain\Event\ProductDraftValueRemoved;

/**
 */
class ProductDraftValueRemovedEventProjector
{
    private const DRAFT_VALUE_TABLE = 'designer.draft_value';

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
     * @param ProductDraftValueRemoved $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDraftValueRemoved $event): void
    {
        $draftId = $event->getAggregateId()->getValue();
        $elementId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();

        $this->delete($draftId, $elementId);
    }

    /**
     * @param string $draftId
     * @param string $elementId
     *
     * @throws DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function delete(string $draftId, string $elementId): void
    {
        $this->connection->delete(
            self::DRAFT_VALUE_TABLE,
            [
                'draft_id' => $draftId,
                'element_id' => $elementId,
            ]
        );
    }
}
