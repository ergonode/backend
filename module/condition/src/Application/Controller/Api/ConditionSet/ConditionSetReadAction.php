<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conditionsets/{conditionSet}", methods={"GET"}, requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
 */
class ConditionSetReadAction
{
    /**
     * @IsGranted("CONDITION_READ")
     *
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
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
     *
     * @ParamConverter(class="Ergonode\Condition\Domain\Entity\ConditionSet")
     *
     * @param ConditionSet $conditionSet
     *
     * @return Response
     */
    public function __invoke(ConditionSet $conditionSet): Response
    {
        return new SuccessResponse($conditionSet);
    }
}
