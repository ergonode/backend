<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Checker;

use Ergonode\Designer\Domain\Entity\Template;

/**
 */
interface TemplateRelationCheckerInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param Template $template
     *
     * @return array
     */
    public function getRelations(Template $template): array;
}
