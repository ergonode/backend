<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Provider;

use Ergonode\Attribute\Application\Form\Attribute\DateAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\GalleryAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\ImageAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\MultiSelectAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\NumericAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\PriceAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\SelectAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\TextareaAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\TextAttributeForm;
use Ergonode\Attribute\Application\Form\Attribute\UnitAttributeForm;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;

/**
 */
class AttributeFormProvider
{
    /**
     * @param string $type
     *
     * @return string
     */
    public function provide(string $type): string
    {
        if (PriceAttribute::TYPE === $type) {
            return PriceAttributeForm::class;
        }

        if (DateAttribute::TYPE === $type) {
            return DateAttributeForm::class;
        }

        if (UnitAttribute::TYPE === $type) {
            return UnitAttributeForm::class;
        }

        if (TextAttribute::TYPE === $type) {
            return TextAttributeForm::class;
        }

        if (TextareaAttribute::TYPE === $type) {
            return TextareaAttributeForm::class;
        }

        if (NumericAttribute::TYPE === $type) {
            return NumericAttributeForm::class;
        }

        if (SelectAttribute::TYPE === $type) {
            return SelectAttributeForm::class;
        }

        if (MultiSelectAttribute::TYPE === $type) {
            return MultiSelectAttributeForm::class;
        }

        if (ImageAttribute::TYPE === $type) {
            return ImageAttributeForm::class;
        }

        if (GalleryAttribute::TYPE === $type) {
            return GalleryAttributeForm::class;
        }

        throw new \RuntimeException(sprintf('Can\' find factory for %s type', $type));
    }
}
