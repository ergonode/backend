<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Transformer\Domain\Command\CreateTransformerCommand;
use Ergonode\Transformer\Domain\Command\GenerateTransformerCommand;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class MapperController
 */
class TransformerController extends AbstractApiController
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
     * @Route("/transformers/create", methods={"POST"})
     *
     * @SWG\Tag(name="Transformer")
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Transformer name",
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
    public function addTransformer(Request $request): Response
    {
        $name = $request->request->get('name');
        $command = new CreateTransformerCommand($name, 'key');
        $this->messageBus->dispatch($command);

        return $this->createRestResponse(['id' => $command->getId()->getValue()], [], Response::HTTP_CREATED);
    }

    /**
     * @Route("/transformers/generate", methods={"POST"})
     *
     * @SWG\Tag(name="Transformer")
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Transformer name",
     * )
     *
     * @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     type="string",
     *     enum={"GKN_ATTRIBUTE", "GKN_VALUE", "GKN_ATTRIBUTE_VALUES", "GKN_PRODUCT", "GKN_PRODUCT_ATTRIBUTE", "LYNKA_ATTRIBUTE", "LYNKA_PRODUCT", "LYNKA_CATEGORIES", "LYNKA_INVENTORY_STOCK"},
     *     description="Transformer generator type",
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
    public function generateAttributeGenerator(Request $request): Response
    {
        $name = $request->request->get('name');
        $type = $request->request->get('type');
        $command = new GenerateTransformerCommand($name, $type, $type);
        $this->messageBus->dispatch($command);

        return $this->createRestResponse(['id' => $command->getId()->getValue()], [], Response::HTTP_CREATED);
    }

    /**
     * @Route("/transformers/{transformer}", methods={"GET"}, requirements={"transformer"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @SWG\Tag(name="Transformer")
     *
     * @SWG\Parameter(
     *     name="transformer",
     *     in="path",
     *     type="string",
     *     description="Transformer id",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns transformer",
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
     * @param Transformer $transformer
     *
     * @ParamConverter(class="Ergonode\Transformer\Domain\Entity\Transformer")
     *
     * @return Response
     *
     */
    public function getTransformer(Transformer $transformer): Response
    {
        return $this->createRestResponse($transformer);
    }
}
