<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Infrastructure\Builder\WorkflowValidatorBuilder;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     name="ergonode_workflow_create",
 *     path="/workflow",
 *     methods={"POST"}
 * )
 */
class WorkflowCreateAction
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
     * @IsGranted("WORKFLOW_CREATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns workflow ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate(
            $data,
            $this->builder->build($data),
            ['Default', WorkflowValidatorBuilder::UNIQUE_WORKFLOW]
        );

        if (0 === $violations->count()) {
            $data['id'] = WorkflowId::generate()->getValue();
            /** @var CreateWorkflowCommand $command */
            $command = $this->serializer->fromArray($data, CreateWorkflowCommand::class);

            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }
}
