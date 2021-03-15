<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Category;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Command\Category\RemoveProductCategoryCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_category_remove",
 *     path="products/{product}/category",
 *     methods={"DELETE"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class RemoveProductCategoryAction
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("PRODUCT_DELETE_CATEGORY")
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
     *     description="Category removed from product",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     */
    public function __invoke(AbstractProduct $product, AbstractCategory $category): Response
    {
        $command = new RemoveProductCategoryCommand($product->getId(), $category->getId());

        $this->commandBus->dispatch($command);

        return new EmptyResponse();
    }
}
