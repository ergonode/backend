<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Delete;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Infrastructure\Validator\Constraint as LanguageAssert;

class DeleteAttributeValueFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=true)
     */
    public ?string $id = null;

    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @LanguageAssert\LanguageCodeConstraint(),
     *     @LanguageAssert\LanguageCodeActive()
     * })
     *
     * @var string[]
     */
    public array $languages = [];
}
