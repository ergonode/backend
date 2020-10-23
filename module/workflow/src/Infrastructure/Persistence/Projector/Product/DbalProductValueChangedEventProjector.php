<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use JMS\Serializer\SerializerInterface;

class DbalProductValueChangedEventProjector
{
    private const TABLE_WORKFLOW_PRODUCT_STATUS = 'product_workflow_status';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $code = $event->getAttributeCode()->getValue();
        if (StatusSystemAttribute::CODE === $code) {
            foreach ($event->getTo()->getValue() as $language => $value) {
                $this->connection->update(
                    self::TABLE_WORKFLOW_PRODUCT_STATUS,
                    [
                        'status_id' => $value,
                    ],
                    [
                        'product_id' => $event->getAggregateId()->getValue(),
                        'language' => $language,
                    ]
                );
            }
        }
    }
}
