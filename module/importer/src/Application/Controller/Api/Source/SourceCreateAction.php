<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Source;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Form\SourceTypeForm;
use Ergonode\Importer\Application\Provider\SourceFormFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
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
    private FormFactoryInterface $formFactory;

    private SourceFormFactoryProvider $provider;

    private CreateSourceCommandBuilderProvider $commandProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        SourceFormFactoryProvider $provider,
        CreateSourceCommandBuilderProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("IMPORT_POST_SOURCE")
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
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $type = $request->get('type');
        $typeForm = $this->formFactory->create(SourceTypeForm::class);
        $typeForm->submit(['type' => $type]);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
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
        throw new FormValidationHttpException($typeForm);
    }
}
