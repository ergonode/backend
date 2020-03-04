<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_transition_delete",
 *     path="/workflow/default/transitions/{source}/{destination}",
 *     methods={"DELETE"}
 * )
 */
class TransitionDeleteAction
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
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
     *     default="EN"
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
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="source")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="destination")
     *
     * @param Workflow $workflow
     * @param Status   $source
     * @param Status   $destination
     *
     * @return Response
     *
     */
    public function __invoke(Workflow $workflow, Status $source, Status $destination): Response
    {
        // @todo add validation
        $command = new DeleteWorkflowTransitionCommand($workflow->getId(), $source->getCode(), $destination->getCode());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
