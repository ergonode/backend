<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Controller\Api\Template;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_designer_template_delete",
 *     path="/templates/{template}",
 *     methods={"DELETE"},
 *     requirements={"templates" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class TemplateDeleteAction
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
     * @IsGranted("DESIGNER_DELETE_TEMPLATE")
     *
     * @SWG\Tag(name="Designer")
     * @SWG\Parameter(
     *     name="template",
     *     in="path",
     *     type="string",
     *     description="Template ID"
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
     *     description="Returns template"
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
    public function __invoke(Template $template): Response
    {
        $relationships = $this->relationshipsResolver->resolve($template->getId());
        if (null !== $relationships) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteTemplateCommand($template->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
