<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model\LanguageTree;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class LanguageTreeUpdateFormModel
{
    /**
     * @var LanguageTreeNodeFormModel
     *
     * @Assert\Valid()
     * @Assert\NotBlank(message="Languages is required")
     */
    public ?LanguageTreeNodeFormModel $languages;

    /**
     */
    public function __construct()
    {
        $this->languages = null;
    }
}
