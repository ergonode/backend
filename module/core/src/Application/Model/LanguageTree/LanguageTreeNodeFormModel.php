<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model\LanguageTree;

use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageCodeConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"LanguageTreeNodeFormModel", "Language"})
 */
class LanguageTreeNodeFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Language is required")
     * @Assert\NotNull()
     *
     * @LanguageCodeConstraint()
     */
    public ?string $language;

    /**
     * @var LanguageTreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public array $children;

    /**
     */
    public function __construct()
    {
        $this->language = null;
        $this->children = [];
    }
}
