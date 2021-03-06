<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Ergonode\BatchAction\Application\Validator\BatchActionFilter;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @BatchActionFilter()
 */
class BatchActionFilterFormModel
{
    /**
     * @Assert\Valid()
     *
     * @JMS\Type("Ergonode\BatchAction\Application\Form\Model\BatchActionFilterIdsFormModel")
     */
    public ?BatchActionFilterIdsFormModel $ids = null;

    /**
     * @JMS\Type("string")
     */
    public ?string $query = null;
}
