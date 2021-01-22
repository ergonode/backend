<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BatchActionFilterFormModel
{
    /**
     * @Assert\Valid()
     */
    public ?BatchActionFilterIdsFormModel $ids = null;

    public ?string $query = null;
}
