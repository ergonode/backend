<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderProvider;

/**
 * @Route(
 *     name="ergonode_source_create",
 *     path="/sources",
 *     methods={"POST"},
 * )
 */
class SourceCreateAction
{
    /**
     * @var SourceFormFactoryProvider
     */
    private SourceFormFactoryProvider $provider;

    /**
     * @var CreateSourceCommandBuilderProvider
     */
    private CreateSourceCommandBuilderProvider $commandProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param SourceFormFactoryProvider          $provider
     * @param CreateSourceCommandBuilderProvider $commandProvider
     * @param CommandBusInterface                $commandBus
     */
    public function __construct(
        SourceFormFactoryProvider $provider,
        CreateSourceCommandBuilderProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("IMPORT_CREATE")
     *
     * @SWG\Tag(name="Import")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import id",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import ID",
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
                $command = $this->commandProvider->provide($type)->build($form);

                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
