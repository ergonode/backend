<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
 *     path="/workflow/default/transitions/{from}/{to}",
 *     methods={"GET"},
 *     requirements={
 *        "from"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "to"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class TransitionReadAction
{
    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_GET_TRANSITION")
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
    public function __invoke(AbstractWorkflow $workflow, Status $from, Status $to): Transition
    {
        if ($workflow->hasTransition($from->getId(), $to->getId())) {
            return $workflow->getTransition($from->getId(), $to->getId());
        }

        throw new NotFoundHttpException();
    }
}
