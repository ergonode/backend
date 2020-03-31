<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class AttributeMapModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Code is to long, It should have {{ limit }} character or less.")
     */
    public ?string $code;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public ?string $attribute;

    /**
     * @param string|null $code
     * @param string|null $attribute
     */
    public function __construct(string $code = null, string $attribute = null)
    {
        $this->code = $code;
        $this->attribute = $attribute;
    }
}
