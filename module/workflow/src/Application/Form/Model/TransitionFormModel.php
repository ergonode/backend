<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Ergonode\Workflow\Application\Validator as WorkflowAssert;
use Ergonode\Condition\Application\Validator as ConditionAssert;
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
    public ?string $source;

    /**
     * @Assert\NotBlank()
     *
     * @WorkflowAssert\StatusExists()
     */
    public ?string $destination;

    /**
     * @Assert\Uuid(strict=true),
     * @ConditionAssert\ConditionSetExists()
     */
    public ?string $conditionSet;

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
        $this->source = null;
        $this->destination = null;
        $this->conditionSet = null;
        $this->roles = [];
    }
}
