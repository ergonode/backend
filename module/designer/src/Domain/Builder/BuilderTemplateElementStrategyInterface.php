<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\View\ViewTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

interface BuilderTemplateElementStrategyInterface
{
    public function isSupported(string $type): bool;

    public function build(TemplateElementInterface $element, Language $language): ViewTemplateElement;
}
