<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Application\Factory\Command\RemoveProductAttributeCommandFactory;
use Ergonode\Product\Application\Form\Product\Attribute\Delete\DeleteProductAttributeCollectionForm;
use Ergonode\Product\Application\Model\Product\Attribute\Delete\DeleteProductAttributeCollectionFormModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Api\Application\Response\EmptyResponse;

/**
 * @Route(
 *     name="ergonode_products_attributes_delete",
 *     path="products/attributes",
 *     methods={"DELETE"},
 *     requirements={
 *         "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *     }
 * )
 */
class DeleteProductsAttributesAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    private RemoveProductAttributeCommandFactory $commandFactory;

    public function __construct(
        FormFactoryInterface $formFactory,
        CommandBusInterface $commandBus,
        RemoveProductAttributeCommandFactory $commandFactory
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @IsGranted("PRODUCT_ATTRIBUTES_DELETE")
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
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
     * )
     *
     * @SWG\Response(
     *     response=204,
     *     description="Delete mass products attribtes",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(
            DeleteProductAttributeCollectionForm::class,
            null,
            ['method' => Request::METHOD_DELETE]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var DeleteProductAttributeCollectionFormModel $model */
            $model = $form->getData();
            foreach ($model->data as $product) {
                $command = $this->commandFactory->create($product);
                $this->commandBus->dispatch($command);
            }

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
