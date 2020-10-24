<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Role;

use Ergonode\Account\Domain\Command\Role\DeleteRoleCommand;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Api\Application\Response\EmptyResponse;
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
 *     name="ergonode_account_role_delete",
 *     path="/roles/{role}",
 *     methods={"DELETE"},
 *     requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class RoleDeleteAction
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
     * @IsGranted("USER_ROLE_DELETE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role ID"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language code"
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
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\Role")
     */
    public function __invoke(Role $role): Response
    {
        if ($role->isHidden()) {
            throw new ConflictHttpException('Can\'t remove hidden role');
        }

        $relationships = $this->relationshipsResolver->resolve($role->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteRoleCommand($role->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
