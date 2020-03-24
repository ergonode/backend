<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class AttributeMapModel
{
    /**
     * @var string
     */
    public ?string $code;

    /**
     * @var string|null
     */
    public ?String $attribute;

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
