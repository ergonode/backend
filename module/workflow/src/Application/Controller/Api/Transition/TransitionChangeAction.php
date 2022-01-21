<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Workflow\Application\Form\Model\TransitionChangeFormModel;
use Ergonode\Workflow\Application\Form\TransitionChangeForm;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

/**
 * @Route(
 *     name="ergonode_workflow_transition_change",
 *     path="/workflow/default/transitions/{from}/{to}",
 *     methods={"PUT"},
 *     requirements={
 *        "from"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "to"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class TransitionChangeAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="from",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="From status id",
     * )
     * @SWG\Parameter(
     *     name="to",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="To status id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Update workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/transition_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(
        AbstractWorkflow $workflow,
        Status $from,
        Status $to,
        Request $request
    ): void {
        try {
            $model = new TransitionChangeFormModel();
            $form = $this->formFactory->create(TransitionChangeForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TransitionChangeFormModel $data */
                $data = $form->getData();

                $roles = [];
                foreach ($data->roles as $role) {
                    $roles[] = new RoleId($role);
                }

                $command = new UpdateWorkflowTransitionCommand(
                    $workflow->getId(),
                    $from->getId(),
                    $to->getId(),
                    $roles,
                    $data->conditionSet ? new ConditionSetId($data->conditionSet) : null
                );

                $this->commandBus->dispatch($command);

                return;
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
