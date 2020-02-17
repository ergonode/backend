<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model;

use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;

/**
 */
class ImporterMagento1ConfigurationModel
{
    /**
     * @var LanguageMapModel[]
     */
    public array $languages;

    /**
     */
    public function __construct()
    {
        $this->languages = [];
    }
}
