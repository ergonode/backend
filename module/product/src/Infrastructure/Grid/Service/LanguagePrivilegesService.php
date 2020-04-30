<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Service;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class LanguagePrivilegesService
{
    /**
     * @param LanguagePrivileges[] $languagePrivilegesCollection
     * @param Language             $language
     *
     * @return bool
     */
    public function isEditableAccessGranted(array $languagePrivilegesCollection, Language $language): bool
    {
        return (isset($languagePrivilegesCollection[$language->getCode()]) && $languagePrivilegesCollection[$language->getCode()]->isEditable());
    }

    /**
     * @param LanguagePrivileges[] $languagePrivilegesCollection
     * @param Language             $language
     *
     * @return bool
     */
    public function isReadableAccessGranted(array $languagePrivilegesCollection, Language $language): bool
    {
        return (isset($languagePrivilegesCollection[$language->getCode()]) && $languagePrivilegesCollection[$language->getCode()]->isReadable());
    }
}
