<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class LanguageMapModel
{
    /**
     * @var string
     */
    public ?string $store;

    /**
     * @var Language|null
     */
    public ?Language $language;

    /**
     */
    public function __construct()
    {
        $this->store = null;
        $this->language = null;
    }
}
