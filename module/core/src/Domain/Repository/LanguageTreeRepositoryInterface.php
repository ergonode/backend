<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\Entity\LanguageTree;

interface LanguageTreeRepositoryInterface
{
    public function load(): ?LanguageTree;


    public function save(LanguageTree $tree): void;
}
