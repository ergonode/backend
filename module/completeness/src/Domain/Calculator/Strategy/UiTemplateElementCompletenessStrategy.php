<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AbstractTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use Ergonode\Editor\Domain\Entity\ProductDraft;

/**
 */
class UiTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    /**
     * @param string $variant
     *
     * @return bool
     */
    public function isSupported(string $variant): bool
    {
        return UiTemplateElementProperty::VARIANT === $variant;
    }

    /**
     * @param ProductDraft                                                     $draft
     * @param Language                                                         $language
     * @param AbstractTemplateElementProperty|AttributeTemplateElementProperty $properties
     *
     * @return CompletenessElementReadModel|null
     */
    public function getElementCompleteness(ProductDraft $draft, Language $language, AbstractTemplateElementProperty $properties): ?CompletenessElementReadModel
    {
        return null;
    }
}
