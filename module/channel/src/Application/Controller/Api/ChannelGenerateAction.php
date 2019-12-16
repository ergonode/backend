<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Channel\Domain\Command\GenerateChannelCommand;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/channels/generate", methods={"POST"})
 */
class ChannelGenerateAction
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("channels/generate", methods={"POST"})
     *
     * @SWG\Tag(name="Channel")
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Channel name",
     * )
     *
     * @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     type="string",
     *     description="Channel generator type",
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return id of created Exporter",
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request",
     *     @SWG\Schema (ref="#/definitions/error")
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @SWG\Schema (ref="#/definitions/error")
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
        $name = $request->request->get('name');
        $type = $request->request->get('type');
        $command = new GenerateChannelCommand($name, $type);
        $this->messageBus->dispatch($command);

        return new CreatedResponse($command->getId());
    }
}
