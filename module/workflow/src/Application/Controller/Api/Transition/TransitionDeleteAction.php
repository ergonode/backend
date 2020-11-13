<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

/**
 * @Route(
 *     name="ergonode_workflow_transition_delete",
 *     path="/workflow/default/transitions/{source}/{destination}",
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
     * @IsGranted("WORKFLOW_DELETE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Source status id",
     * )
     * @SWG\Parameter(
     *     name="destination",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Destination status id",
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
    public function __invoke(AbstractWorkflow $workflow, Status $source, Status $destination): Response
    {
        $command = new DeleteWorkflowTransitionCommand($workflow->getId(), $source->getId(), $destination->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
