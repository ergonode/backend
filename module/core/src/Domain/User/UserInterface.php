<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\User;

use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface
{
    public function getId(): AggregateId;
    public function isActive(): bool;
    /**
     * @return LanguagePrivileges[]
     */
    public function getLanguagePrivilegesCollection(): array;
}
