<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\User;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\User\UserInterface as DomainUserInterface;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends SymfonyUserInterface, DomainUserInterface
{
    public function isActive(): bool;
    public function getEmail(): Email;
    public function getRoleId(): RoleId;
    public function getFirstName(): string;
    public function getLastName(): string;
    public function getLanguage(): Language;
    /**
     * @return LanguagePrivileges[]
     */
    public function getLanguagePrivilegesCollection(): array;
    public function hasReadLanguagePrivilege(Language $language): bool;
    public function hasEditLanguagePrivilege(Language $language): bool;
}
