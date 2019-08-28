<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Query;

use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface TemplateGroupQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(): array;

    /**
     * @return TemplateGroupId
     */
    public function getDefaultId(): TemplateGroupId;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
