<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Provider;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Application\Form\Property\UnitAttributePropertyForm;
use Ergonode\Attribute\Application\Form\Property\DateAttributePropertyForm;
use Ergonode\Attribute\Application\Form\Property\PriceAttributePropertyForm;

/**
 */
class AttributePropertyFormResolver
{
    /**
     * @param string $type
     *
     * @return string|null
     */
    public function resolve(string $type): ?string
    {
        if (UnitAttribute::TYPE === $type) {
            return UnitAttributePropertyForm::class;
        }

        if (DateAttribute::TYPE === $type) {
            return DateAttributePropertyForm::class;
        }

        if (PriceAttribute::TYPE === $type) {
            return PriceAttributePropertyForm::class;
        }

        return null;
    }
}
