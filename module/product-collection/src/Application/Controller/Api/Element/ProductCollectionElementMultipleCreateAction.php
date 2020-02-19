<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\Element;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ProductCollection\Application\Form\ProductCollectionElementMultipleCreateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionElementMultipleCreateFormModel;
use Ergonode\ProductCollection\Domain\Command\AddMultipleProductCollectionElementCommand;
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
 *     name="ergonode_product_collection_element_multiple_create",
 *     path="/collections/{collection}/elements",
 *     methods={"POST"},
 *     requirements={"collection"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 * )
 */
class ProductCollectionElementMultipleCreateAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param CommandBusInterface   $commandBus
     * @param ProductQueryInterface $productQuery
     * @param FormFactoryInterface  $formFactory
     */
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
     * @IsGranted("PRODUCT_COLLECTION_CREATE")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="collection",
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
     * @ParamConverter(class="Ergonode\ProductCollection\Domain\Entity\ProductCollection")
     *
     * @param ProductCollection $productCollection
     * @param Request           $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(ProductCollection $productCollection, Request $request): Response
    {
        try {
            $model = new ProductCollectionElementMultipleCreateFormModel();
            $form = $this->formFactory->create(ProductCollectionElementMultipleCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionElementMultipleCreateFormModel $data */
                $data = $form->getData();

                $productIds = $this->productQuery->findProductIdsBySegments($data->segments);

                if ($data->skus) {
                    $skus = array_map('trim', explode(',', $data->skus));
                    $productIdsFromSkus = $this->productQuery->findProductIdsBySkus($skus);
                    $productIds = array_unique(array_merge($productIds, $productIdsFromSkus));
                }

                $command = new AddMultipleProductCollectionElementCommand(
                    $productCollection->getId(),
                    $productIds
                );

                $this->commandBus->dispatch($command);

                return new CreatedResponse($productCollection->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
