<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_transition_read",
 *     path="/workflow/default/transitions/{source}/{destination}",
 *     methods={"GET"}
 * )
 */
class TransitionReadAction
{
    /**
     * @IsGranted("WORKFLOW_READ")
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
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns status",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="source")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="destination")
     *
     * @param Workflow $workflow
     * @param Status   $source
     * @param Status   $destination
     *
     * @return Response
     */
    public function __invoke(Workflow $workflow, Status $source, Status $destination): Response
    {
        if ($workflow->hasTransition($source->getCode(), $destination->getCode())) {
            return new SuccessResponse($workflow->getTransition($source->getCode(), $destination->getCode()));
        }

        throw new NotFoundHttpException();
    }
}
