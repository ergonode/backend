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
     * @param string|null   $store
     * @param Language|null $language
     */
    public function __construct(string $store = null, Language $language = null)
    {
        $this->store = $store;
        $this->language = $language;
    }
}
