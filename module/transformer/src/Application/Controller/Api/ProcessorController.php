<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Command\CreateProcessorCommand;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ProcessorController extends AbstractApiController
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
     * @Route("/processors", methods={"POST"})
     *
     * @SWG\Tag(name="Transformer")
     *
     * @SWG\Parameter(
     *     name="import",
     *     in="formData",
     *     type="string",
     *     description="Import Id",
     * )
     * @SWG\Parameter(
     *     name="transformer",
     *     in="formData",
     *     type="string",
     *     description="Transformer Id",
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     type="string",
     *     enum={"ATTRIBUTE", "PRODUCT", "CATEGORY", "VALUE", "ATTRIBUTE_VALUE", "IMAGE"},
     *     description="Processor action",
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Return id of created Transformer",
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
    public function addProcessor(Request $request): Response
    {
        $import = $request->request->get('import');
        $transformer = $request->request->get('transformer');
        $action = $request->request->get('action');

        $command = new CreateProcessorCommand(new ImportId($import), new TransformerId($transformer), $action);
        $this->messageBus->dispatch($command);

        return $this->createRestResponse(['id' => $command->getId()->getValue()], [], Response::HTTP_CREATED);
    }
}
