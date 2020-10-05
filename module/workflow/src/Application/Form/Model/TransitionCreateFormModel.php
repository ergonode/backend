<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Account\Infrastructure\Validator\RoleExists;
use Ergonode\Workflow\Infrastructure\Validator as ErgoAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

/**
 */
class TransitionCreateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ErgoAssert\StatusIdNotExists()
     */
    public ?string $source;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     *
     * @ErgoAssert\StatusIdNotExists()
     */
    public ?string $destination;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=100,
     *      maxMessage="Status name is to long, It should have {{ limit }} character or less."
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
     *       maxMessage="Status description is to long,. It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $description;

    /**
     * @var string|null
     */
    public ?string $conditionSet;

    /**
     * @var AbstractWorkflow
     */
    private AbstractWorkflow $workflow;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true),
     *
     *     @RoleExists()
     *
     * })
     */
    public array $roles;

    /**
     * @param AbstractWorkflow $workflow
     */
    public function __construct(
        AbstractWorkflow $workflow
    ) {
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
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
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

            if ($data->workflow->hasTransition($source, $destination)) {
                $context->addViolation('Transition exists');
            }
        }
    }
}
