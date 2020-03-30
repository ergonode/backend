<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\AttributeGroup;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_attribute_group_delete",
 *     path="/attributes/groups/{group}",
 *     methods={"DELETE"},
 *     requirements={"group" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeGroupDeleteAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var RelationshipsResolverInterface
     */
    private RelationshipsResolverInterface $relationshipsResolver;

    /**
     * @var ExistingRelationshipMessageBuilderInterface
     */
    private ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder;

    /**
     * @param CommandBusInterface                         $commandBus
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
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
     * @IsGranted("ATTRIBUTE_GROUP_DELETE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="group",
     *     in="path",
     *     type="string",
     *     description="Attribute group id"
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Successful removing attribute"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Attribute not exists"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Existing relationships"
     * )
     *
     * @param AttributeGroup $group
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AttributeGroup")
     *
     * @return Response
     */
    public function __invoke(AttributeGroup $group): Response
    {
        $relationships = $this->relationshipsResolver->resolve($group->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteAttributeGroupCommand($group->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
