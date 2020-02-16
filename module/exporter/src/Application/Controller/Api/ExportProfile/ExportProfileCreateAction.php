<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\ExportProfile\CreateExportProfileCommand;
use Ergonode\Exporter\Infrastructure\Builder\ExportProfileValidatorBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     path="/export-profile",
 *     methods={"POST"}
 * )
 */
class ExportProfileCreateAction
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
     * @param ValidatorInterface            $validator
     * @param ExportProfileValidatorBuilder $builder
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        ValidatorInterface $validator,
        ExportProfileValidatorBuilder $builder,
        CommandBusInterface $commandBus
    ) {
        $this->validator = $validator;
        $this->builder = $builder;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("EXPORT_PROFILE_CREATE")
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
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();

        $constraint = $this->builder->build($data);
        $violations = $this->validator->validate($data, $constraint);

        if (0 === $violations->count()) {
            $command = new CreateExportProfileCommand(
                $data['name'],
                $data['type'],
                $data['params']
            );

            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }
}
