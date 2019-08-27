<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Product\Application\Form\ProductCreateForm;
use Ergonode\Product\Application\Form\ProductUpdateForm;
use Ergonode\Product\Application\Model\ProductCreateFormModel;
use Ergonode\Product\Application\Model\ProductUpdateFormModel;
use Ergonode\Product\Domain\Command\CreateProductCommand;
use Ergonode\Product\Domain\Command\UpdateProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Infrastructure\Grid\ProductGrid;
use Ergonode\Product\Persistence\Dbal\DataSet\DbalProductDataSet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ProductController extends AbstractController
{
    /**
     * @var DbalProductDataSet
     */
    private $dataSet;

    /**
     * @var ProductGrid
     */
    private $productGrid;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param DbalProductDataSet  $dataSet
     * @param ProductGrid         $productGrid
     * @param MessageBusInterface $messageBus
     */
    public function __construct(DbalProductDataSet $dataSet, ProductGrid $productGrid, MessageBusInterface $messageBus)
    {
        $this->dataSet = $dataSet;
        $this->productGrid = $productGrid;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("products", methods={"GET"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"sku","index","template"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="columns",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Columns"
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getProducts(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse($this->productGrid, $configuration, $this->dataSet, $language);
    }

    /**
     * @Route("products/{product}", methods={"GET"})
     *
     * @IsGranted("PRODUCT_READ")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="product ID",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param AbstractProduct $product
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     * @return Response
     */
    public function getProduct(AbstractProduct $product): Response
    {
        return new SuccessResponse($product);
    }

    /**
     * @Route("products", methods={"POST"})
     *
     * @IsGranted("PRODUCT_CREATE")
     *
     * @SWG\Tag(name="Product")
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
     * @throws \Exception
     */
    public function createProduct(Request $request): Response
    {
        $model = new ProductCreateFormModel();
        $form = $this->createForm(ProductCreateForm::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductCreateFormModel $data */
            $data = $form->getData();
            $command = new CreateProductCommand(new Sku($data->sku), new TemplateId($data->template), $data->categories);
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("products/{product}", methods={"PUT"})
     *
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
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add product",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product")
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
     * @throws \Exception
     */
    public function updateProduct(AbstractProduct $product, Request $request): Response
    {
        $model = new ProductUpdateFormModel();
        $form = $this->createForm(ProductUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductCreateFormModel $data */
            $data = $form->getData();
            $command = new UpdateProductCommand($product->getId(), $data->categories);
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
