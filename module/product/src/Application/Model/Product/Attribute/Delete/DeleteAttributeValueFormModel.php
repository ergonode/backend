<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Delete;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Application\Validator as LanguageAssert;
use Ergonode\Attribute\Application\Validator as AttributeAssert;

class DeleteAttributeValueFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=true)
     * @AttributeAssert\AttributeExists()
     */
    public ?string $id = null;

    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @LanguageAssert\LanguageCode(),
     *     @LanguageAssert\LanguageCodeActive()
     * })
     *
     * @var string[]
     */
    public array $languages = [];
}
