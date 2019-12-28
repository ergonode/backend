<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180619083800 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS category (
               id UUID NOT NULL,
               name JSONB NOT NULL, 
               code VARCHAR(255) DEFAULT NULL, 
               sequence SERIAL, 
               PRIMARY KEY(id)
            )
        ');

        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CATEGORY_CREATE', 'Category']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CATEGORY_READ', 'Category']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CATEGORY_UPDATE', 'Category']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CATEGORY_DELETE', 'Category']
        );

        $this->createEventStoreEvents([
            'Ergonode\Category\Domain\Event\CategoryCreatedEvent' => 'Category created',
            'Ergonode\Category\Domain\Event\CategoryNameChangedEvent' => 'Category name changed',
            'Ergonode\Category\Domain\Event\CategoryDeletedEvent' => 'Category deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->connection->insert('event_store_event', [
                'id' => Uuid::uuid4()->toString(),
                'event_class' => $class,
                'translation_key' => $translation,
            ]);
        }
    }
}
