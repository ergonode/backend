<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use Ramsey\Uuid\Uuid;

/**
 */
interface AttributeRepositoryInterface
{
    /**
     * @param Uuid $id
     *
     * @return ExportAttribute
     */
    public function load(Uuid $id): ?ExportAttribute;

    /**
     * @param ExportAttribute $attribute
     */
    public function save(ExportAttribute $attribute): void;
}
