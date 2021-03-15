<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_condition_conditionset_read",
 *     path="/conditionsets/{conditionSet}",
 *     methods={"GET"},
 *     requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ConditionSetReadAction
{
    /**
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="conditionSet",
     *     in="path",
     *     type="string",
     *     description="Conditionset ID"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns conditionset"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(ConditionSet $conditionSet): Response
    {
        return new SuccessResponse($conditionSet);
    }
}
