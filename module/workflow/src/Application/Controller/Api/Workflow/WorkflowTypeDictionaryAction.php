<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Workflow\Application\Provider\WorkflowTypeProvider;

/**
 * @Route(
 *     name="ergonode_workflow_type_dictionary",
 *     path="dictionary/workflow-type",
 *     methods={"GET"}
 * )
 */
class WorkflowTypeDictionaryAction
{
    private WorkflowTypeProvider $provider;

    private TranslatorInterface $translator;

    public function __construct(WorkflowTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @SWG\Tag(name="Dictionary")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns workflow types"
     * )
     *
     *
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        $dictionary = [];
        $types = $this->provider->provide();
        foreach ($types as $type) {
            $dictionary[$type] = $this->translator->trans($type, [], 'workflow');
        }

        return new SuccessResponse($dictionary);
    }
}
