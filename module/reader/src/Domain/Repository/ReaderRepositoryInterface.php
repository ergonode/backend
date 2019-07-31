<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Reader\Domain\Repository;

use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;

/**
 */
interface ReaderRepositoryInterface
{
    /**
     * @param ReaderId $id
     *
     * @return Reader|null
     */
    public function load(ReaderId $id): ?Reader;

    /**
     * @param Reader $reader
     *
     * @return void
     */
    public function save(Reader $reader): void;

    /**
     * @param ReaderId $id
     *
     * @return bool
     */
    public function exists(ReaderId $id): bool;
}
