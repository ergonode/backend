<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\ExportProfile\DeleteExportProfileCommand;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route(
 *     name="ergonode_export_profile_delete",
 *     path="/export-profile/{exportProfile}",
 *     methods={"DELETE"},
 *     requirements={"exportProfile"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ExportProfileDeleteAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

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
     * @param ExportProfileRepositoryInterface            $repository
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ExportProfileRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->commandBus = $commandBus;
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
    }


    /**
     * @IsGranted("EXPORT_PROFILE_DELETE")
     *
     * @SWG\Tag(name="Export Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en"
     * )
     * @SWG\Parameter(
     *     name="exportProfile",
     *     in="path",
     *     type="string",
     *     description="Export Profile Id",
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
     * @param AbstractExportProfile $exportProfile
     *
     * @ParamConverter(class="Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile")
     *
     * @return Response
     */
    public function __invoke(AbstractExportProfile $exportProfile): Response
    {
        $relationships = $this->relationshipsResolver->resolve($exportProfile->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }
        $command = new DeleteExportProfileCommand($exportProfile->getId());
        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
