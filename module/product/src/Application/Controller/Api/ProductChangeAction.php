<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Application\Provider\ProductFormProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Provider\UpdateProductCommandFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_change",
 *     path="products/{product}",
 *     methods={"PUT"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductChangeAction
{
    private FormFactoryInterface $formFactory;

    private ProductFormProvider $provider;

    private UpdateProductCommandFactoryProvider $commandProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        ProductFormProvider $provider,
        UpdateProductCommandFactoryProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->provider = $provider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("PRODUCT_PUT")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product ID",
     * )
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
     *     @SWG\Schema(ref="#/definitions/product_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Product updated",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Request $request): Response
    {
        $class = $this->provider->provide($product->getType());

        $form = $this->formFactory->create($class, null, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory = $this->commandProvider->provide($product->getType());
            $command = $factory->create($product->getId(), $form);
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
