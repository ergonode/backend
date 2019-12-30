<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20180101000000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE event_store (
                id BIGSERIAL NOT NULL, 
                aggregate_id uuid NOT NULL, 
                sequence int NOT NULL, 
                event_id UUID NOT NULL,
                payload jsonb NOT NULL, 
                recorded_by uuid default NULL, 
                recorded_at timestamp without time zone NOT NULL, 
                CONSTRAINT event_store_pkey PRIMARY KEY (id)
            )
        ');
        $this->addSql(
            'CREATE UNIQUE INDEX event_store_unique_key ON event_store USING btree (aggregate_id, sequence)'
        );

        $this->addSql('
            CREATE TABLE event_store_history (
                id BIGSERIAL NOT NULL, 
                aggregate_id uuid NOT NULL, 
                sequence int NOT NULL,
                variant int NOT NULL DEFAULT 1,
                event_id UUID NOT NULL, 
                payload jsonb NOT NULL, 
                recorded_by uuid default NULL, 
                recorded_at timestamp without time zone NOT NULL, 
                CONSTRAINT event_store_history_pkey PRIMARY KEY (id)
            )
        ');
        $this->addSql(
            'CREATE UNIQUE INDEX event_store_history_unique_key 
                    ON event_store_history USING btree (aggregate_id, sequence, variant)'
        );

        $this->addSql('
            CREATE TABLE event_store_event (
                id UUID NOT NULL, 
                event_class character varying(255) NOT NULL, 
                translation_key text NOT NULL,
                CONSTRAINT event_store_event_pkey PRIMARY KEY (id)
            )
        ');
        $this->addSql(
            'CREATE UNIQUE INDEX event_store_event_unique_key ON event_store_event USING btree (event_class)'
        );
    }
}
