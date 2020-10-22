<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\View\ViewTemplateElement;

interface BuilderTemplateElementStrategyInterface
{
    /**
     * @param string $variant
     * @param string $type
     *
     * @return bool
     */
    public function isSupported(string $variant, string $type): bool;

    /**
     * @param TemplateElement $element
     *
     * @param Language        $language
     *
     * @return ViewTemplateElement
     */
    public function build(TemplateElement $element, Language $language): ViewTemplateElement;
}
