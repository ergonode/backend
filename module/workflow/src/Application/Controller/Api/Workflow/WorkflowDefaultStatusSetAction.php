<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

/**
 * @Route(
 *     name="ergonode_workflow_default_status_set",
 *     path="/workflow/default/status/{status}/default",
 *     methods={"PUT"}
 * )
 */
class WorkflowDefaultStatusSetAction
{
    private CommandBusInterface $commandBus;

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
     *     default="en_GB",
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     */
    public function __invoke(AbstractWorkflow $workflow, Status $status): Response
    {
        $command = new SetDefaultStatusCommand($workflow->getId(), $status->getId());

        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
