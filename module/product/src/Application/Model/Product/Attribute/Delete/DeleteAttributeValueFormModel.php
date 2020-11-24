<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Delete;

use Symfony\Component\Validator\Constraints as Assert;

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
     *     @Assert\Regex("/^[a-z]{2}_[A-Z]{2}$/")
     * })
     *
     * @var string[]
     */
    public array $languages = [];
}
