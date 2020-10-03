<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class StoreViewModel
{
    /**
     * @var Language|null
     */
    public ?Language $defaultLanguage = null;

    /**
     * @var LanguageMapModel[]
     *
     * @Assert\Valid()
     */
    public array $languages = [];
}
