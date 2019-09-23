<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\Controller\Api;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Transformer\Domain\Command\CreateTransformerCommand;
use Ergonode\Transformer\Domain\Command\DeleteTransformerCommand;
use Ergonode\Transformer\Domain\Command\GenerateTransformerCommand;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class TransformerController extends AbstractController
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $repository;

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
     * @param TransformerRepositoryInterface              $repository
     * @param MessageBusInterface                         $messageBus
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        TransformerRepositoryInterface $repository,
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
    }

    /**
     * @Route("/transformers/{transformer}", methods={"GET"}, requirements={"transformer"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @SWG\Tag(name="Transformer")
     * @SWG\Parameter(
     *     name="transformer",
     *     in="path",
     *     type="string",
     *     description="Transformer id"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns transformer"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Transformer not found"
     * )
     *
     * @param Transformer $transformer
     *
     * @ParamConverter(class="Ergonode\Transformer\Domain\Entity\Transformer")
     *
     * @return Response
     *
     */
    public function getTransformer(Transformer $transformer): Response
    {
        return new SuccessResponse($transformer);
    }

    /**
     * @Route("/transformers/create", methods={"POST"})
     *
     * @SWG\Tag(name="Transformer")
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Transformer name"
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Return id of created Transformer"
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function addTransformer(Request $request): Response
    {
        $name = $request->request->get('name');
        // @todo Why key is const?
        $command = new CreateTransformerCommand($name, 'key');
        $this->messageBus->dispatch($command);

        return new CreatedResponse($command->getId());
    }

    /**
     * @Route("/transformers/generate", methods={"POST"})
     *
     * @SWG\Tag(name="Transformer")
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Transformer name"
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     type="string",
     *     description="Transformer generator type"
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Return id of created Transformer"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Transformer exists",
     *     @SWG\Schema(ref="#/definitions/error_message")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function generateAttributeGenerator(Request $request): Response
    {
        $name = $request->request->get('name');
        $type = $request->request->get('type');

        $id = TransformerId::fromKey($type);

        if (!$this->repository->exists($id)) {
            $command = new GenerateTransformerCommand($name, $type, $type);
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ConflictHttpException(sprintf('Transformer "%s" already exists', $name));
    }

    /**
     * @Route("/transformers/{transformer}", methods={"DELETE"}, requirements={"transformer"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @SWG\Tag(name="Transformer")
     * @SWG\Parameter(
     *     name="transformer",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Transformer ID"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Transformer\Domain\Entity\Transformer")
     *
     * @param Transformer $transformer
     *
     * @return Response
     */
    public function deleteTransformer(Transformer $transformer): Response
    {
        $relationships = $this->relationshipsResolver->resolve($transformer->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteTransformerCommand($transformer->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
