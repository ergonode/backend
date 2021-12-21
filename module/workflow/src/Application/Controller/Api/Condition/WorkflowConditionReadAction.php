<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Condition;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionDictionaryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_condition_dictionary_read",
 *     path="/dictionary",
 *     methods={"GET"}
 * )
 */
class WorkflowConditionReadAction
{
    private WorkflowConditionDictionaryProvider $provider;

    public function __construct(WorkflowConditionDictionaryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_GET_CONDITION_DICTIONARY")
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns dictionary of available workflow conditions"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Language $language): array
    {
        return $this->provider->getDictionary($language);
    }
}
