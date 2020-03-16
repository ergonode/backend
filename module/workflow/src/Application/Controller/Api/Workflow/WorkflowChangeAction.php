<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Infrastructure\Builder\WorkflowValidatorBuilder;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     name="ergonode_workflow_change",
 *     path="/workflow/default",
 *     methods={"PUT"}
 * )
 */
class WorkflowChangeAction
{
    /**
     * @var WorkflowValidatorBuilder
     */
    private WorkflowValidatorBuilder $builder;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param WorkflowValidatorBuilder $builder
     * @param ValidatorInterface       $validator
     * @param SerializerInterface      $serializer
     * @param MessageBusInterface      $messageBus
     */
    public function __construct(
        WorkflowValidatorBuilder $builder,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        MessageBusInterface $messageBus
    ) {
        $this->builder = $builder;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
    }

    /**
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
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
     *
     * @param Workflow $workflow
     * @param Request  $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Workflow $workflow, Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->builder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = $workflow->getId()->getValue();
            $command = $this->serializer->fromArray($data, UpdateWorkflowCommand::class);

            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
