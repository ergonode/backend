<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\ImportError;

interface ImportErrorRepositoryInterface
{
    public function add(ImportError $error): void;
}
