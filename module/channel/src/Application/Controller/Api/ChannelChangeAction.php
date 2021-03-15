<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderProvider;

/**
 * @Route(
 *     name="ergonode_channel_change",
 *     path="/channels/{channel}",
 *     methods={"PUT"},
 *     requirements={"channel" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ChannelChangeAction
{
    private ChannelFormFactoryProvider $provider;

    private UpdateChannelCommandBuilderProvider $commandProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        ChannelFormFactoryProvider $provider,
        UpdateChannelCommandBuilderProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("CHANNEL_PUT")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="channel",
     *     in="path",
     *     type="string",
     *     description="Channel id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add Channel",
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
     *     response=200,
     *     description="Returns Channel",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Channel\Domain\Entity\AbstractChannel")
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(AbstractChannel $channel, Request $request): Response
    {
        $type = $channel->getType();

        try {
            $form = $this->provider->provide($type)->create($channel);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->commandProvider->provide($type)->build($channel->getId(), $form);
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }
        throw new FormValidationHttpException($form);
    }
}
