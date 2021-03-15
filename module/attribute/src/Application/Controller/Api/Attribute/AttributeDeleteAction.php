<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_attribute_delete",
 *     path="/attributes/{attribute}",
 *     methods={"DELETE"},
 *     requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeDeleteAction
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
     * @IsGranted("ATTRIBUTE_DELETE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id"
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
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
     */
    public function __invoke(AbstractAttribute $attribute): Response
    {
        $relationships = $this->relationshipsResolver->resolve($attribute->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteAttributeCommand($attribute->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
