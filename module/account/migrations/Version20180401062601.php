<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180401062601 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
                id UUID NOT NULL, 
                first_name VARCHAR(128) NOT NULL,
                last_name VARCHAR(128) NOT NULL,
                avatar_id UUID DEFAULT NULL,
                username VARCHAR(128) NOT NULL, 
                password VARCHAR(41) NOT NULL, 
                role_id UUID NOT NULL,
                language VARCHAR(2) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX users_username_key ON users (username)');

        $this->addSql('
            CREATE TABLE privileges (
                id UUID NOT NULL, 
                code VARCHAR(128) NOT NULL,
                area VARCHAR(128) NOT NULL,              
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX privileges_name_key ON privileges (code)');

        $this->addSql('
            CREATE TABLE roles (
                id UUID NOT NULL, 
                name VARCHAR(100) NOT NULL,
                description VARCHAR(500) NOT NULL,          
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX role_name_key ON roles (name)');

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_ROLE_CREATE', 'Role']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_ROLE_READ', 'Role']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_ROLE_UPDATE', 'Role']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_ROLE_DELETE', 'Role']);

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_CREATE', 'User']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_READ', 'User']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_UPDATE', 'User']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'USER_DELETE', 'User']);

        $this->addSql('ALTER TABLE roles ADD privileges json DEFAULT NULL');

        $this->addSql('ALTER TABLE users ADD is_active BOOLEAN DEFAULT TRUE NOT NULL');

        $this->createEventStoreEvents([
            'Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent' => 'User avatar changed',
            'Ergonode\Account\Domain\Event\User\UserCreatedEvent' => 'User created',
            'Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent' => 'User first name changed',
            'Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent' => 'User language changed',
            'Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent' => 'User last name changed',
            'Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent' => 'User password changed',
            'Ergonode\Account\Domain\Event\User\UserRoleChangedEvent' => 'User role changed',
            'Ergonode\Account\Domain\Event\User\UserActivatedEvent' => 'User activated',
            'Ergonode\Account\Domain\Event\User\UserDeactivatedEvent' => 'User disabled',
            'Ergonode\Account\Domain\Event\Role\AddPrivilegeToRoleEvent' => 'Privilege added',
            'Ergonode\Account\Domain\Event\Role\RemovePrivilegeFromRoleEvent' => 'Privilege removed',
            'Ergonode\Account\Domain\Event\Role\RoleCreatedEvent' => 'Role created',
            'Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent' => 'Role name changed',
            'Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent' => 'Role description changed',
            'Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent' => 'List of privileges changed',
            'Ergonode\Account\Domain\Event\Role\RoleDeletedEvent' => 'Role deleted',
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
