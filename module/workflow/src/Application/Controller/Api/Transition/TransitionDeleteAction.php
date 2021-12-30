<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

/**
 * @Route(
 *     name="ergonode_workflow_transition_delete",
 *     path="/workflow/default/transitions/{from}/{to}",
 *     methods={"DELETE"}
 * )
 */
class TransitionDeleteAction
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_DELETE_TRANSITION")
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
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Request status parameter not correct"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Status not found"
     * )
     */
    public function __invoke(AbstractWorkflow $workflow, Status $from, Status $to): void
    {
        $command = new DeleteWorkflowTransitionCommand($workflow->getId(), $from->getId(), $to->getId());
        $this->commandBus->dispatch($command);
    }
}
