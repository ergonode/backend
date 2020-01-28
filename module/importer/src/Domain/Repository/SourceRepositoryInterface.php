<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Source\SourceId;

/**
 */
interface SourceRepositoryInterface
{
    /**
     * @param SourceId $id
     *
     * @return AbstractSource|null
     *
     * @throws \ReflectionException
     */
    public function load(SourceId $id): ?AbstractSource;

    /**
     * @param AbstractSource $import
     */
    public function save(AbstractSource $import): void;

    /**
     * @param SourceId $id
     *
     * @return bool
     */
    public function exists(SourceId $id): bool;
}
