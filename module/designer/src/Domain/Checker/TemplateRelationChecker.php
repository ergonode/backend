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
class TemplateRelationChecker
{
    /**
     * @var TemplateRelationCheckerInterface[]
     */
    private $checkers;

    /**
     * @param TemplateRelationCheckerInterface ...$checkers
     */
    public function __construct(TemplateRelationCheckerInterface ...$checkers)
    {
        $this->checkers = $checkers;
    }

    /**
     * @param Template $template
     *
     * @return bool
     */
    public function hasRelations(Template $template): bool
    {
        return !empty($this->getRelations($template));
    }

    /**
     * @param Template $template
     *
     * @return array
     */
    public function getRelations(Template $template): array
    {
        $result = [];

        foreach ($this->checkers as $checker) {
            $relations = $checker->getRelations($template);
            if (!empty($relations)) {
                $result[$checker->getType()] = $relations;
            }
        }

        return $result;
    }
}
