<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Editor\Domain\Entity\ProductDraft;

/**
 */
interface TemplateElementCompletenessStrategyInterface
{
    /**
     * @param string $variant
     *
     * @return bool
     */
    public function supports(string $variant): bool;

    /**
     * @param ProductDraft                                                      $draft
     * @param Language                                                          $language
     * @param TemplateElementPropertyInterface|AttributeTemplateElementProperty $properties
     *
     * @return CompletenessElementReadModel|null
     */
    public function getElementCompleteness(
        ProductDraft $draft,
        Language $language,
        TemplateElementPropertyInterface $properties
    ): ?CompletenessElementReadModel;
}
