<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Status;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_workflow_status_delete",
 *     path="/status/{status}",
 *     methods={"DELETE"}
 * )
 */
class StatusDeleteAction
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
     * @IsGranted("WORKFLOW_DELETE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="status",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Status code"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Request status parameter not correct"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Status not found"
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @throws \Exception
     */
    public function __invoke(Status $status): Response
    {
        $relationships = $this->relationshipsResolver->resolve($status->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteStatusCommand($status->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
