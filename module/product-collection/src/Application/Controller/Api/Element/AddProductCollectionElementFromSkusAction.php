<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\Element;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\ProductCollection\Application\Model\ProductCollectionElementFromSkusFormModel;
use Ergonode\ProductCollection\Application\Form\ProductCollectionElementFromSkusForm;
use Ergonode\ProductCollection\Domain\Command\AddProductCollectionElementsCommand;

/**
 * @Route(
 *     name="ergonode_product_collection_element_add_from_skus",
 *     path="/collections/{productCollection}/elements/add-from-skus",
 *     methods={"POST"},
 *     requirements={"productCollection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 * )
 */
class AddProductCollectionElementFromSkusAction
{
    private CommandBusInterface $commandBus;

    private ProductQueryInterface $productQuery;

    private FormFactoryInterface $formFactory;

    public function __construct(
        CommandBusInterface $commandBus,
        ProductQueryInterface $productQuery,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->productQuery = $productQuery;
        $this->formFactory = $formFactory;
    }


    /**
     * @IsGranted("PRODUCT_COLLECTION_POST_ELEMENT_SKU")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="productCollection",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Product collection ID",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/element_create_by_segment_and_sku")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns product collection ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function __invoke(ProductCollection $productCollection, Request $request): Response
    {
        try {
            $model = new ProductCollectionElementFromSkusFormModel();
            $form = $this->formFactory->create(ProductCollectionElementFromSkusForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionElementFromSkusFormModel $data */
                $data = $form->getData();

                $productIds = $this->productQuery->findProductIdsBySkus($data->skus);

                $command = new AddProductCollectionElementsCommand($productCollection->getId(), $productIds);

                $this->commandBus->dispatch($command);

                return new CreatedResponse($productCollection->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
