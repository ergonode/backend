<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\ExportProfile\UpdateExportProfileCommand;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Builder\ExportProfileValidatorBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     path="/export-profile/{exportProfile}",
 *     methods={"PUT"},
 *     requirements={"exportProfile"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ExportProfileChangeAction
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var ExportProfileValidatorBuilder
     */
    private ExportProfileValidatorBuilder $builder;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @param ValidatorInterface               $validator
     * @param ExportProfileValidatorBuilder    $builder
     * @param CommandBusInterface              $commandBus
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(
        ValidatorInterface $validator,
        ExportProfileValidatorBuilder $builder,
        CommandBusInterface $commandBus,
        ExportProfileRepositoryInterface $repository
    ) {
        $this->validator = $validator;
        $this->builder = $builder;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }


    /**
     * @IsGranted("EXPORT_PROFILE_UPDATE")
     *
     * @SWG\Tag(name="Export Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code"
     * )
     * @SWG\Parameter(
     *     name="exportProfile",
     *     in="path",
     *     type="string",
     *     description="Export Profile Id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Create export profile",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/export_profile")
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Returns created profile ID"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @param AbstractExportProfile $exportProfile
     * @param Request               $request
     *
     * @ParamConverter(class="Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractExportProfile $exportProfile, Request $request): Response
    {
        $data = $request->request->all();
        $violations = $this->validator->validate($data, $this->builder->build($data));
        if (0 === $violations->count()) {
            $command = new UpdateExportProfileCommand(
                $exportProfile->getId(),
                $data['name'],
                $data['type'],
                $data['params']
            );

            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }
        throw new ViolationsHttpException($violations);
    }
}
