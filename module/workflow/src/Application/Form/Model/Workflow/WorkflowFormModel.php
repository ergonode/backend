<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model\Workflow;

use Ergonode\Workflow\Application\Form\Model\TransitionFormModel;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Workflow\Application\Validator as WorkflowAssert;

class WorkflowFormModel
{
    /**
     * @Assert\NotBlank(groups={"Create"})
     * @Assert\Length(
     *     max=100,
     *     maxMessage="Workflow name is too long. It should contain {{ limit }} characters or less.",
     *     groups={"Create"}
     *     )
     * @WorkflowAssert\WorkflowExists(groups={"Create"})
     */
    public ?string $code = null;

    /**
     * @var string[]
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *
     *     @WorkflowAssert\StatusExists(),
     * })
     */
    public array $statuses = [];

    /**
     * @var TransitionFormModel[]
     *
     * @Assert\Valid()
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     * })
     */
    public array $transitions = [];
}
