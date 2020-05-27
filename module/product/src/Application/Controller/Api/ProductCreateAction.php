<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Application\Provider\ProductFormProvider;
use Ergonode\Product\Infrastructure\Provider\CreateProductCommandFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Product\Domain\Entity\SimpleProduct;

/**
 * @Route("products", methods={"POST"})
 */
class ProductCreateAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var ProductFormProvider
     */
    private ProductFormProvider $provider;

    /**
     * @var CreateProductCommandFactoryProvider
     */
    private CreateProductCommandFactoryProvider $commandProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface                $formFactory
     * @param ProductFormProvider                 $provider
     * @param CreateProductCommandFactoryProvider $commandProvider
     * @param CommandBusInterface                 $commandBus
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ProductFormProvider $provider,
        CreateProductCommandFactoryProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("PRODUCT_CREATE")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add product",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Create product",
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
        $type = $request->request->get('type', SimpleProduct::TYPE);
        $request->request->remove('type');
        $class = $this->provider->provide($type);

        $form = $this->formFactory->create($class, null, ['validation_groups' => ['Default', 'Create']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $factory = $this->commandProvider->provide($type);
            $command = $factory->create($form);
            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }
}
