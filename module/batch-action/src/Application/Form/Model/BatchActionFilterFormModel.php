<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Ergonode\BatchAction\Application\Validator\BatchActionFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @BatchActionFilter()
 */
class BatchActionFilterFormModel
{
    /**
     * @Assert\Valid()
     */
    public ?BatchActionFilterIdsFormModel $ids = null;

    public ?string $query = null;
}
