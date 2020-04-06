<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_workflow_default_status_set",
 *     path="/workflow/default/status/{status}/default",
 *     methods={"PUT"}
 * )
 */
class WorkflowDefaultStatusSetAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="status",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Status code",
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
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @param Workflow $workflow
     * @param Status   $status
     *
     * @return Response
     *
     */
    public function __invoke(Workflow $workflow, Status $status): Response
    {
        $command = new SetDefaultStatusCommand($workflow->getId(), $status->getCode());

        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
