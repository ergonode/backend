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
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

interface AggregateUserInterface extends UserInterface
{
    public function getId(): UserId;
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
