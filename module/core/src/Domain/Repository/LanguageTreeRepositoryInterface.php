<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\Entity\LanguageTree;

interface LanguageTreeRepositoryInterface
{
    /**
     * @return LanguageTree|null
     */
    public function load(): ?LanguageTree;


    /**
     * @param LanguageTree $tree
     */
    public function save(LanguageTree $tree): void;
}
