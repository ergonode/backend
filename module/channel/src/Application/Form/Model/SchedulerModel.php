<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Form\Model;

use Ergonode\Channel\Application\Validator\Scheduler;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Scheduler(groups={"Active"})
 */
class SchedulerModel
{
    /**
     * @Assert\NotNull(message="Activity is required")
     * @Assert\Type("bool")
     */
    public bool $active = false;

    /**
     * @Assert\NotBlank(message="Start date is required", groups={"Active"})
     */
    public ?\DateTime $start = null;

    /**
     * @Assert\NotBlank(message="Hour is required", groups={"Active"})
     * @Assert\Range(min=0, max=2147483647, groups={"Active"})
     */
    public ?int $hour = null;

    /**
     * @Assert\NotBlank(message="Minute is required", groups={"Active"})
     * @Assert\Range(min=0, max=59, groups={"Active"})
     */
    public ?int $minute = null;
}
