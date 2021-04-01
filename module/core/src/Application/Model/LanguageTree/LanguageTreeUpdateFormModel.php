<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Model\LanguageTree;

use Ergonode\Core\Application\Validator as CoreAssert;
use Symfony\Component\Validator\Constraints as Assert;

class LanguageTreeUpdateFormModel
{
    /**
     * @Assert\Valid()
     * @Assert\NotBlank(message="Languages is required")
     *
     * @CoreAssert\LanguageTreeLanguageRelation()
     */
    public ?LanguageTreeNodeFormModel $languages;

    public function __construct()
    {
        $this->languages = null;
    }
}
