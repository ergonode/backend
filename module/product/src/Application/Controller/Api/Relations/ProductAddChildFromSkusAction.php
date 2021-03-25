<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Relations;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Application\Form\Product\Relation\ProductChildBySkusForm;
use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySkusFormModel;
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_child_add_from_skus",
 *     path="products/{product}/children/add-from-skus",
 *     methods={"POST"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductAddChildFromSkusAction extends AbstractController
{
    private ProductQueryInterface $query;

    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(
        ProductQueryInterface $query,
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_POST_RELATIONS_CHILDREN_SKU")
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
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     */
    public function __invoke(Language $language, AbstractProduct $product, Request $request): Response
    {
        try {
            $model = new ProductChildBySkusFormModel($product);
            $form = $this->formFactory->create(
                ProductChildBySkusForm::class,
                $model,
                ['validation_groups' => [$product->getType()]]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductChildBySkusFormModel $data */
                $data = $form->getData();

                $productIds = $this->query->findProductIdsBySkus($data->skus);

                $command = new AddProductChildrenCommand($product, $productIds);

                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
