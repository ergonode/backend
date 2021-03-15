<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipTypeMessageBuilder;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_segment_delete",
 *     path="/segments/{segment}",
 *     methods={"DELETE"},
 *     requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SegmentDeleteAction
{
    private RelationshipsResolverInterface $relationshipsResolver;

    private ExistingRelationshipTypeMessageBuilder $existingRelationshipTypeMessageBuilder;

    private CommandBusInterface $commandBus;

    public function __construct(
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipTypeMessageBuilder $existingRelationshipTypeMessageBuilder,
        CommandBusInterface $commandBus
    ) {
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipTypeMessageBuilder = $existingRelationshipTypeMessageBuilder;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("SEGMENT_DELETE")
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="segment",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Segment ID",
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
    public function __invoke(Segment $segment): Response
    {
        $relationships = $this->relationshipsResolver->resolve($segment->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipTypeMessageBuilder->build($relationships));
        }

        $command = new DeleteSegmentCommand($segment->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
