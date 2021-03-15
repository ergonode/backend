<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\User;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;

interface LanguageCollectionAwareInterface
{
    /**
     * @return LanguagePrivileges[]
     */
    public function getLanguagePrivilegesCollection(): array;
    public function hasReadLanguagePrivilege(Language $language): bool;
    public function hasEditLanguagePrivilege(Language $language): bool;
}
