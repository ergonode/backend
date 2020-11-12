<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Transformer;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Importer\Domain\Command\CreateTransformerCommand;
use Ergonode\Importer\Domain\Command\DeleteTransformerCommand;
use Ergonode\Importer\Domain\Entity\Transformer;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

class TransformerController extends AbstractController
{
    private CommandBusInterface $commandBus;

    private RelationshipsResolverInterface $relationshipsResolver;

    private ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder;

    public function __construct(
        CommandBusInterface $commandBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->commandBus = $commandBus;
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
     *
     *
     * @throws \Exception
     */
    public function addTransformer(Request $request): Response
    {
        $name = $request->request->get('name');
        // @todo Why key is const?
        $command = new CreateTransformerCommand($name, 'key');
        $this->commandBus->dispatch($command);

        return new CreatedResponse($command->getId());
    }

    /**
     * @Route(
     *     name="ergonode_transformer_delete",
     *     path="/transformers/{transformer}",
     *     methods={"DELETE"},
     *     requirements={"transformer"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
     * )
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
     */
    public function deleteTransformer(Transformer $transformer): Response
    {
        $relationships = $this->relationshipsResolver->resolve($transformer->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteTransformerCommand($transformer->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
