<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\Element;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ProductCollection\Application\Form\ProductCollectionElementUpdateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionElementUpdateFormModel;
use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionElementCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_element_change",
 *     path="/collections/{collection}/elements/{product}",
 *     methods={"PUT"},
 *     requirements={
 *     "collection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *      "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     },
 * )
 */
class ProductCollectionElementChangeAction
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
    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_UPDATE")
     *
     * @SWG\Tag(name="Product Collection")
     * * @SWG\Parameter(
     *     name="collection",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Collection Id",
     * )
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Product Id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Update workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/element_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     * @ParamConverter(class="Ergonode\ProductCollection\Domain\Entity\ProductCollection")
     *
     * @param ProductCollection $productCollection
     * @param AbstractProduct   $product
     * @param Request           $request
     *
     * @return Response
     */
    public function __invoke(ProductCollection $productCollection, AbstractProduct $product, Request $request): Response
    {
        try {
            $model = new ProductCollectionElementUpdateFormModel();
            $form = $this->formFactory->create(
                ProductCollectionElementUpdateForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionElementUpdateFormModel $data */
                $data = $form->getData();
                $command = new UpdateProductCollectionElementCommand(
                    $productCollection->getId(),
                    $product->getId(),
                    $data->visible
                );

                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
