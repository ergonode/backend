<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20180731143300 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE designer.product (
                product_id UUID NOT NULL,
                template_id UUID NOT NULL,                               
                PRIMARY KEY(product_id, template_id)
            )
        ');

        $this->addSql('
            CREATE TABLE designer.draft (
                id UUID NOT NULL,
                sku VARCHAR(255) DEFAULT NULL,
                type VARCHAR(16) NOT NULL DEFAULT \'NEW\',
                product_id UUID DEFAULT NULL,      
                applied boolean NOT NULL DEFAULT FALSE,                                          
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE designer.draft_value (
                id UUID NOT NULL,
                draft_id UUID DEFAULT NULL,
                element_id UUID NOT NULL,
                language VARCHAR(2) DEFAULT NULL, 
                value text NOT NULL,                                           
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('ALTER TABLE designer.product ADD CONSTRAINT product_template_id_fk FOREIGN KEY (template_id) REFERENCES designer.template (id) ON DELETE RESTRICT');

        $this->createEventStoreEvents([
            'Ergonode\Editor\Domain\Event\ProductDraftApplied' => 'Applied product draft',
            'Ergonode\Editor\Domain\Event\ProductDraftCreated' => 'Product draft created',
            'Ergonode\Editor\Domain\Event\ProductDraftValueAdded' => 'Value added to product draft',
            'Ergonode\Editor\Domain\Event\ProductDraftValueChanged' => 'Product draft value changed',
            'Ergonode\Editor\Domain\Event\ProductDraftValueRemoved' => 'Product draft value removed',
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
                'event_class' => $class,
                'translation_key' => $translation,
            ]);
        }
    }
}
