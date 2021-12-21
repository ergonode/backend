<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Ergonode\Workflow\Application\Validator as WorkflowAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @WorkflowAssert\TransitionValid()
 */
class TransitionFormModel
{
    /**
     * @Assert\NotBlank()
     *
     * @WorkflowAssert\StatusExists()
     */
    public ?string $from;

    /**
     * @Assert\NotBlank()
     *
     * @WorkflowAssert\StatusExists()
     */
    public ?string $to;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true),
     *
     *     @AccountAssert\RoleExists()
     * })
     */
    public array $roles;

    public function __construct()
    {
        $this->from = null;
        $this->to = null;
        $this->roles = [];
    }
}
