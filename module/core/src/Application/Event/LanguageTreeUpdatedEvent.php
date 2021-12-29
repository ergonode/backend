<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Event;

use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class LanguageTreeUpdatedEvent implements ApplicationEventInterface
{
    private LanguageTree $tree;

    public function __construct(LanguageTree $tree)
    {
        $this->tree = $tree;
    }

    public function getTree(): LanguageTree
    {
        return $this->tree;
    }
}
