<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_source_delete",
 *     path="/sources/{source}",
 *     methods={"DELETE"},
 *     requirements={"source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SourceDeleteAction
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
     * @IsGranted("IMPORT_DELETE_SOURCE")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     description="Source id",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Successful removing source"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Source not exists"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(AbstractSource $source): Response
    {
        $relationships = $this->relationshipsResolver->resolve($source->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteSourceCommand($source->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
