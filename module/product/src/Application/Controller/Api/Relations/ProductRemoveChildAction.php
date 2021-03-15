<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Relations;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Command\Relations\RemoveProductChildCommand;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_child_remove",
 *     path="products/{product}/children/{child}",
 *     methods={"DELETE"},
 *     requirements={
 *          "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *          "child"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *     }
 * )
 */
class ProductRemoveChildAction
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("PRODUCT_DELETE_RELATIONS_CHILD")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product ID",
     * )
     * @SWG\Parameter(
     *     name="child",
     *     in="path",
     *     type="string",
     *     description="Child ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Child removed",
     * )
     * @param AbstractProduct|AbstractAssociatedProduct $product
     */
    public function __invoke(Language $language, AbstractProduct $product, AbstractProduct $child): Response
    {
        $this->commandBus->dispatch(new RemoveProductChildCommand($product, $child));

        return new EmptyResponse();
    }
}
