<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211219110000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS audit (
                id uuid NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                created_by UUID DEFAULT NULL,
                edited_at TIMESTAMP WITH TIME ZONE NOT NULL,
                edited_by UUID DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');

        $this->updateAudit();
        $eventId = $this->getEventId(ProductValueAddedEvent::class);
        $this->removeEvents($eventId, 'esa_created_at');
        $this->removeEvents($eventId, 'esa_created_by');
        $this->removeEvents($eventId, 'esa_edited_at');
        $this->removeEvents($eventId, 'esa_edited_by');
        $eventId = $this->getEventId(ProductValueChangedEvent::class);
        $this->removeEvents($eventId, 'esa_edited_at');
        $this->removeEvents($eventId, 'esa_edited_by');

        $this->removeValues('esa_created_at');
        $this->removeValues('esa_created_by');
        $this->removeValues('esa_edited_at');
        $this->removeValues('esa_edited_by');

        $this->addSql('ALTER TABLE product DROP column created_at');
        $this->addSql('ALTER TABLE product DROP column updated_at');

        $this->addSql('DELETE FROM event_store_snapshot WHERE aggregate_id in (SELECT id FROM product)');
    }

    private function getEventId(string $event): string
    {
        return $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                ':class' => $event,
            ]
        )->fetchOne();
    }

    private function removeEvents(string $eventId, string $code): void
    {
        $this->connection->executeQuery(
            'DELETE FROM event_store WHERE event_id = ? and payload::TEXT ilike \'%'.$code.'%\'',
            [$eventId]
        );
    }

    private function removeValues(string $code): void
    {
        $attributeId = $this->connection->executeQuery(
            'SELECT id FROM attribute WHERE code = :code',
            [
                ':code' => $code,
            ]
        )->fetchOne();

        if ($attributeId) {
            $this->addSql(
                'DELETE FROM value_translation WHERE value_id IN 
                    (SELECT value_id FROM product_value WHERE attribute_id = ?)',
                [$attributeId,]
            );

            $this->addSql(
                'DELETE FROM product_value WHERE attribute_id = ?', [$attributeId]
            );
        }
    }

    private function updateAudit():void
    {
        $eventId = $this->getEventId(ProductCreatedEvent::class);

        $this->addSql('INSERT INTO audit (id, created_at, edited_at) SELECT id, created_at, updated_at FROM product');

        foreach ($this->getProducts() as $product) {
            $this->addSql('UPDATE audit SET created_by = 
                (SELECT recorded_by FROM event_store WHERE aggregate_id = ? AND event_id = ?)',
                [$product, $eventId]
            );

            $this->addSql('UPDATE audit SET edited_by = 
                (SELECT recorded_by FROM event_store WHERE aggregate_id = ? ORDER BY sequence DESC LIMIT 1)',
                [$product]
            );
        }
    }

    private function getProducts(): array
    {
        return $this->connection->executeQuery('SELECT id FROM product')->fetchFirstColumn();
    }
}
