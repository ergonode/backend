<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Infrastructure\Builder\WorkflowValidatorBuilder;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 */
class WorkflowController extends AbstractController
{
    /**
     * @var WorkflowValidatorBuilder
     */
    private $builder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SerializerInterface|Serializer
     */
    private $serializer;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @var ExistingRelationshipMessageBuilderInterface
     */
    private $existingRelationshipMessageBuilder;

    /**
     * @param WorkflowValidatorBuilder                    $builder
     * @param ValidatorInterface                          $validator
     * @param SerializerInterface                         $serializer
     * @param MessageBusInterface                         $messageBus
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        WorkflowValidatorBuilder $builder,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->builder = $builder;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->messageBus = $messageBus;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
    }

    /**
     * @Route("/workflow/default", methods={"GET"})
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
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
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     *
     * @param Workflow $workflow
     *
     * @return Response
     */
    public function getWorkflow(Workflow $workflow): Response
    {
        return new SuccessResponse($workflow);
    }

    /**
     * @Route("/workflow", methods={"POST"})
     *
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
    public function createWorkflow(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->builder->build($data), ['Default', WorkflowValidatorBuilder::UNIQUE_WORKFLOW]);

        if (0 === $violations->count()) {
            $data['id'] = WorkflowId::fromCode($data['code'])->getValue();
            $command = $this->serializer->fromArray($data, CreateWorkflowCommand::class);

            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }

    /**
     * @Route(path="/workflow/default", methods={"PUT"})
     *
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
    public function updateWorkflow(Workflow $workflow, Request $request): Response
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

    /**
     * @Route("/workflow/default", methods={"DELETE"}, requirements={"workflow"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("WORKFLOW_DELETE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="workflow",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Workflow ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     *
     * @param Workflow $workflow
     *
     * @return Response
     */
    public function deleteWorkflow(Workflow $workflow): Response
    {
        $relationships = $this->relationshipsResolver->resolve($workflow->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteWorkflowCommand($workflow->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
