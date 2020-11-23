<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Attribute;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Product\Application\Form\Product\Attribute\ProductAttributeCollectionForm;
use Ergonode\Product\Application\Model\Product\Attribute\ProductAttributeCollectionFormModel;
use Ergonode\Product\Application\Factory\Command\ChangeProductAttributeCommandFactory;
use Ergonode\Api\Application\Response\SuccessResponse;

/**
 * @Route(
 *     name="ergonode_products_attributes_update",
 *     path="products/attributes",
 *     methods={"POST"},
 *     requirements={
 *         "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *     }
 * )
 */
class UpdateProductAttributesAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    private ChangeProductAttributeCommandFactory $commandFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        CommandBusInterface $commandBus,
        ChangeProductAttributeCommandFactory $commandFactory
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->commandFactory = $commandFactory;
    }


    /**
     * @IsGranted("PRODUCT_UPDATE")
     *
     * @SWG\Tag(name="Product")
     *
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
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add category ID",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_category")
     * )
     *
     * @SWG\Response(
     *     response=204,
     *     description="Update mass pproducts attribtes",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ProductAttributeCollectionForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProductAttributeCollectionFormModel $data */
            $data = $form->getData();
            foreach ($data->data as $product) {
                $command = $this->commandFactory->create($product);
                $this->commandBus->dispatch($command);
            }

            return new SuccessResponse();
        }


        throw new FormValidationHttpException($form);
    }
}
