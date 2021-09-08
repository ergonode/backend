<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20191104140000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE comment (
                id UUID NOT NULL,
                object_id UUID NOT NULL,               
                author_id UUID NOT NULL,
                created_at timestamp with time zone NOT NULL,
                edited_at timestamp with time zone DEFAULT NULL,
                content VARCHAR(4000) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql(
            'ALTER TABLE comment
                    ADD CONSTRAINT comment_users_fk FOREIGN KEY (author_id) 
                    REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE'
        );

        $this->createEventStoreEvents([
            'Ergonode\Comment\Domain\Event\CommentCreatedEvent' => 'Comment created',
            'Ergonode\Comment\Domain\Event\CommentContentChangedEvent' => 'Comment content changed',
            'Ergonode\Comment\Domain\Event\CommentDeletedEvent' => 'Comment deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
