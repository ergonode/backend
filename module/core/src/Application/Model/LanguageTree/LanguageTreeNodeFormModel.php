<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Model\LanguageTree;

use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageIdExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"LanguageTreeNodeFormModel", "Language"})
 */
class LanguageTreeNodeFormModel
{
    /**
     * @Assert\NotBlank(message="Language is required")
     * @Assert\NotNull()
     *
     * @LanguageIdExists(groups={"Language"}))
     */
    public ?string $languageId;

    /**
     * @var LanguageTreeNodeFormModel[]
     *
     * @Assert\Valid()
     */
    public array $children;

    public function __construct()
    {
        $this->languageId = null;
        $this->children = [];
    }
}
