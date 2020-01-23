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
final class Version20191112111500 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE notification (
                id UUID NOT NULL,
                created_at timestamp without time zone NOT NULL,      
                author_id UUID DEFAULT NULL,
                message TEXT NOT NULL,
                parameters JSONB DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE users_notification (
                recipient_id UUID NOT NULL,
                notification_id UUID NOT NULL,      
                read_at timestamp without time zone DEFAULT NULL,
                PRIMARY KEY(recipient_id, notification_id)
            )
        ');

        $this->addSql('
            ALTER TABLE users_notification
                ADD CONSTRAINT user_notifications_notification_fk 
                FOREIGN KEY (notification_id) REFERENCES notification ON DELETE CASCADE');
    }
}
