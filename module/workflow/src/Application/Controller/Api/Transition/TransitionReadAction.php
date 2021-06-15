<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Transition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

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
     * @IsGranted("WORKFLOW_GET_TRANSITION")
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
     *     default="en_GB",
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
     */
    public function __invoke(AbstractWorkflow $workflow, Status $source, Status $destination): Transition
    {
        if ($workflow->hasTransition($source->getId(), $destination->getId())) {
            return $workflow->getTransition($source->getId(), $destination->getId());
        }

        throw new NotFoundHttpException();
    }
}
