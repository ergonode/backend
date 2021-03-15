<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Query;

interface LanguageTreeQueryInterface
{
    /**
     * @return array
     */
    public function getTree(): array;
}
