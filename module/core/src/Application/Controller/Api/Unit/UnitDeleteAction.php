<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\Unit;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Domain\Command\DeleteUnitCommand;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_unit_delete",
 *     path="/units/{unit}",
 *     methods={"DELETE"},
 *     requirements={"unit"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class UnitDeleteAction
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
     * @IsGranted("CORE_DELETE_UNIT")
     *
     * @SWG\Tag(name="Unit")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="unit",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Unit ID",
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
     */
    public function __invoke(Unit $unit): Response
    {
        $relations = $this->relationshipsResolver->resolve($unit->getId());
        if (null !== $relations) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relations));
        }

        $command = new DeleteUnitCommand($unit->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
