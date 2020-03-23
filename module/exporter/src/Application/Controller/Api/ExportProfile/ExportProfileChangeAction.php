<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryProvider;
use Ergonode\Exporter\Domain\Command\ExportProfile\CreateExportProfileCommand;
use Ergonode\Exporter\Domain\Command\ExportProfile\UpdateExportProfileCommand;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route(
 *     path="/export-profile/{exportProfile}",
 *     methods={"POST"},
 *     requirements={"exportProfile"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ExportProfileChangeAction
{
    /**
     * @var ExportProfileFormFactoryProvider
     */
    private ExportProfileFormFactoryProvider $provider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ExportProfileFormFactoryProvider $provider
     * @param CommandBusInterface              $commandBus
     */
    public function __construct(ExportProfileFormFactoryProvider $provider, CommandBusInterface $commandBus)
    {
        $this->provider = $provider;
        $this->commandBus = $commandBus;
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
        try {
            $form = $this->provider->provide($exportProfile->getType())->create($exportProfile);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                /** @var CreateExportProfileCommand $data */
                $data = $form->getData();

                $command = new UpdateExportProfileCommand(
                    $exportProfile->getId(),
                    $data->getName(),
                    $data->getType(),
                    $data->getParameters()
                );
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
