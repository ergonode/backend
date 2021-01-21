<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BatchActionFilterIdsFormModel
{
    /**
     * @var string[]
     *
     * @Assert\Count(min=1),
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true)
     * })
     */
    public array $list = [];

    /**
     * @Assert\NotBlank
     */
    public ?bool $included = null;
}
