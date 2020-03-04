<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Status;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_status_delete",
 *     path="/status/{status}",
 *     methods={"DELETE"}
 * )
 */
class StatusDeleteAction
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var RelationshipsResolverInterface
     */
    private RelationshipsResolverInterface $relationshipsResolver;

    /**
     * @var ExistingRelationshipMessageBuilderInterface
     */
    private ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder;

    /**
     * @param MessageBusInterface                         $messageBus
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->messageBus = $messageBus;
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
     *     default="EN"
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @param Status $status
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Status $status): Response
    {
        $relationships = $this->relationshipsResolver->resolve($status->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteStatusCommand($status->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
