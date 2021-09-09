<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model\Attribute;

use Ergonode\Attribute\Application\Model\Attribute\Property\TextareaAttributePropertyModel;
use Symfony\Component\Validator\Constraints as Assert;

class TextareaAttributeFormModel extends AttributeFormModel
{
    /**
     * @Assert\Valid()
     */
    public TextareaAttributePropertyModel $parameters;

    public function __construct()
    {
        $this->parameters = new TextareaAttributePropertyModel();
    }
}
