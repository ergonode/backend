<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\Controller\Api;

use Ergonode\Core\Application\Response\CreatedResponse;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Command\CreateProcessorCommand;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ProcessorController extends AbstractController
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
     * @SWG\Response(
     *     response=201,
     *     description="Return id of created Transformer",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @todo Validation required
     */
    public function addProcessor(Request $request): Response
    {
        $import = $request->request->get('import');
        $transformer = $request->request->get('transformer');
        $action = $request->request->get('action');

        $command = new CreateProcessorCommand(new ImportId($import), new TransformerId($transformer), $action);
        $this->messageBus->dispatch($command);

        return new CreatedResponse($command->getId()->getValue());
    }
}
