<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Application\Validator as WorkflowAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class TransitionCreateFormModel
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
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=100,
     *      maxMessage="Status name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=500,
     *       maxMessage="Status descriptionis too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $description;

    public ?string $conditionSet;

    private AbstractWorkflow $workflow;

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

    public function __construct(AbstractWorkflow $workflow)
    {
        $this->source = null;
        $this->destination = null;
        $this->name = [];
        $this->description = [];
        $this->conditionSet = null;
        $this->workflow = $workflow;
        $this->roles = [];
    }

    /**
     * @Assert\Callback()
     *
     * @param mixed $payload
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        /** @var TransitionCreateFormModel $data */
        $data = $context->getValue();

        if (!StatusId::isValid((string) $data->source)) {
            $context->addViolation('Source not valid');
        } elseif (!StatusId::isValid((string) $data->destination)) {
            $context->addViolation('Destination not valid');
        } else {
            $source = new StatusId($data->source);
            $destination = new StatusId($data->destination);

            if ($data->getWorkflow()->hasTransition($source, $destination)) {
                $context->addViolation('Transition exists');
            }
        }
    }

    public function getWorkflow(): AbstractWorkflow
    {
        return $this->workflow;
    }
}
