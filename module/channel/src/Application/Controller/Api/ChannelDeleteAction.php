<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Channel\Domain\Command\DeleteChannelCommand;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 * @Route(
 *     name="ergonode_channel_delete",
 *     path="/channels/{channel}",
 *     methods={"DELETE"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelDeleteAction
{
    private RelationshipsResolverInterface $relationshipsResolver;

    private ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder;

    private CommandBusInterface $commandBus;

    public function __construct(
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder,
        CommandBusInterface $commandBus
    ) {
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("CHANNEL_DELETE")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="channel",
     *     in="path",
     *     type="string",
     *     description="Channel id"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Successful removing channel"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Channel not exists"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Existing relationships"
     * )
     *
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     *
     *
     * @throws \Exception
     */
    public function __invoke(AbstractChannel $channel): Response
    {
        $relationships = $this->relationshipsResolver->resolve($channel->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteChannelCommand($channel->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
