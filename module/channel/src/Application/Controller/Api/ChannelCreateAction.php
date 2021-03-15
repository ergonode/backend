<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Channel\Application\Form\ChannelTypeForm;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderProvider;

/**
 * @Route("/channels", methods={"POST"})
 */
class ChannelCreateAction
{
    private FormFactoryInterface $formFactory;

    private ChannelFormFactoryProvider $provider;

    private CreateChannelCommandBuilderProvider $commandProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        ChannelFormFactoryProvider $provider,
        CreateChannelCommandBuilderProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("CHANNEL_POST")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add channel",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/channel")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns channel ID",
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
        $typeForm = $this->formFactory->create(ChannelTypeForm::class);
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
