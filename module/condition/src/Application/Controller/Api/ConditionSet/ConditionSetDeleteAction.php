<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_condition_conditionset_delete",
 *     path="/conditionsets/{conditionSet}",
 *     methods={"DELETE"},
 *     requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ConditionSetDeleteAction
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
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="conditionSet",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Condition set ID"
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
    public function __invoke(ConditionSet $conditionSet): Response
    {
        $relationships = $this->relationshipsResolver->resolve($conditionSet->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteConditionSetCommand($conditionSet->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
