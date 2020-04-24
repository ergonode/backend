<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Product\Application\Form\ProductUpdateForm;
use Ergonode\Product\Application\Model\ProductCreateFormModel;
use Ergonode\Product\Application\Model\ProductUpdateFormModel;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Command\UpdateProductCategoriesCommand;

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
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_UPDATE")
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
     *     default="en"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add product",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_upd")
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
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @param AbstractProduct $product
     * @param Request         $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractProduct $product, Request $request): Response
    {
        $model = new ProductUpdateFormModel();
        $form = $this->formFactory->create(ProductUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductCreateFormModel $data */
            $data = $form->getData();
            $categories = [];
            foreach ($data->categories as $category) {
                $categories[] = new CategoryId($category);
            }
            $command = new UpdateProductCategoriesCommand($product->getId(), $categories);
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
