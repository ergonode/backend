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
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Product\Application\Model\Product\Attribute\AttributeValueFormModel;
use Ergonode\Product\Application\Form\Product\Attribute\ProductAttributeForm;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Route(
 *     name="ergonode_product_category_add",
 *     path="products/{product}/attribute",
 *     methods={"POST"},
 *     requirements={
 *         "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *         "attribute"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class UpdateProductAttributesAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface $formFactory
     * @param CommandBusInterface  $commandBus
     */
    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
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
//        if ($attribute->getScope()->isGlobal()) {
//            $root = $this->query->getRootLanguage();
//            if (!$root->isEqual($language)) {
//                throw new AccessDeniedHttpException();
//            }
//        }

        $data = new AttributeValueFormModel();

        $form = $this->formFactory->create(ProductAttributeForm::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($form->getData());
            die;
        }

        throw new FormValidationHttpException($form);
    }
}
