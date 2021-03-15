<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Application\Form\Product\ProductTypeForm;
use Ergonode\Product\Application\Provider\ProductFormProvider;
use Ergonode\Product\Infrastructure\Provider\CreateProductCommandFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("products", methods={"POST"})
 */
class ProductCreateAction
{
    private FormFactoryInterface $formFactory;

    private ProductFormProvider $provider;

    private CreateProductCommandFactoryProvider $commandProvider;

    private CommandBusInterface $commandBus;

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
     * @IsGranted("PRODUCT_POST")
     *
     * @SWG\Tag(name="Product")
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
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $type = $request->request->get('type');
        $typeForm = $this->formFactory->create(ProductTypeForm::class);
        $typeForm->submit(['type' => $type]);
        if (!$typeForm->isSubmitted() || !$typeForm->isValid()) {
            throw new FormValidationHttpException($typeForm);
        }
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
