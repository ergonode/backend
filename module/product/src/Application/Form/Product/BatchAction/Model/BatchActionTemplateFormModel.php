<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\BatchAction\Model;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Ergonode\BatchAction\Application\Validator\AllFilterDisabled;
use Symfony\Component\Validator\Constraints as Assert;

class BatchActionTemplateFormModel
{
    /**
     * @Assert\Valid()
     * @Assert\NotBlank()
     * @AllFilterDisabled()
     *
     * @var string|BatchActionFilterFormModel $filter
     */
    public $filter = null;
}
