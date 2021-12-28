<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Handler;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\User\UserInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Security;

abstract class AbstractAuditEventHandler
{
    protected Connection $connection;

    protected Security $security;

    public function __construct(Connection $connection, Security $security)
    {
        $this->connection = $connection;
        $this->security = $security;
    }

    protected function getUser(): ?UserId
    {
        $user = $this->security->getUser();

        if ($user instanceof UserInterface) {
            return $user->getId();
        }

        return null;
    }
}
