<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Condition;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\Configuration\WorkflowConditionConfiguration;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionConfigurationProvider;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_condition_configuration_read",
 *     path="/condition/{condition}",
 *     methods={"GET"}
 * )
 */
class WorkflowConditionConfigurationReadAction
{
    private WorkflowConditionConfigurationProvider $provider;

    public function __construct(WorkflowConditionConfigurationProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="condition",
     *     in="path",
     *     type="string",
     *     description="Condition ID"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns condition"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language, string $condition): WorkflowConditionConfiguration
    {
        try {
            return $this->provider->getConfiguration($language, $condition);
        } catch (\RuntimeException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }
}
