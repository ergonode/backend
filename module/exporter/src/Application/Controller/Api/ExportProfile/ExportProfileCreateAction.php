<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Controller\Api\ExportProfile;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     path="/export-profile",
 *     methods={"POST"}
 * )
 */
class ExportProfileCreateAction
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
        $type = $request->get('type');

        try {
            $form = $this->provider->provide($type)->create();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $form->getData();
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }
        throw new FormValidationHttpException($form);
    }
}
