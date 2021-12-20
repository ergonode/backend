<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Handler;

use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Core\Application\Security\User\CachedUser;
use Ergonode\Core\Application\Security\Security;

class ProductCreatedEventHandler
{
    private const TABLE = 'audit';

    private Connection $connection;

    private Security $security;

    public function __construct(Connection $connection, Security $security)
    {
        $this->connection = $connection;
        $this->security = $security;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $createdAt = (new \DateTime())->format(\DateTime::W3C);
        $createdBy = $this->getUser();

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getProduct()->getId()->getValue(),
                'created_at' => $createdAt,
                'edited_at' => $createdAt,
                'created_by' => $createdBy,
                'edited_by' => $createdBy,
            ]
        );
    }

    private function getUser(): ?UserId
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            return $user->getId();
        }

        if ($user instanceof CachedUser) {
            return $user->getId();
        }

        return null;
    }
}
