<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ramsey\Uuid\Uuid;

/**
 */
interface TreeRepositoryInterface
{
    /**
     * @param Uuid $id
     *
     * @return ExportTree|null
     */
    public function load(Uuid $id): ?ExportTree;

    /**
     * @param ExportTree $exportTree
     */
    public function save(ExportTree $exportTree): void;

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function exists(Uuid $id): bool;

    /**
     * @param ExportTree $exportTree
     */
    public function delete(ExportTree $exportTree): void;
}
