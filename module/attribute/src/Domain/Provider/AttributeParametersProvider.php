<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

/**
 */
class AttributeParametersProvider
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return array
     */
    public function provide(AbstractAttribute $attribute): array
    {
        $parameters = $attribute->getParameters();
        if (isset($parameters['options'])) {
            unset($parameters['options']);
        }

        return $parameters;
    }
}
