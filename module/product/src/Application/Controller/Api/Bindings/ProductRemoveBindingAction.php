<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Bindings;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Product\Domain\Command\Bindings\RemoveProductBindingCommand;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(
 *     name="ergonode_product_bind_remove",
 *     path="products/{product}/binding/{binding}",
 *     methods={"DELETE"},
 *     requirements={
 *          "product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *          "binding"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *     }
 * )
 */
class ProductRemoveBindingAction
{
    private CommandBusInterface $commandBus;

    private ValidatorInterface $validator;

    public function __construct(CommandBusInterface $commandBus, ValidatorInterface $validator)
    {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }


    /**
     * @IsGranted("PRODUCT_DELETE_BINDING")
     *
     * @SWG\Tag(name="Product")
     * @SWG\Parameter(
     *     name="product",
     *     in="path",
     *     type="string",
     *     description="Product ID",
     * )
     * @SWG\Parameter(
     *     name="binding",
     *     in="path",
     *     type="string",
     *     description="Attribute ID",
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
     * @param AbstractProduct|AbstractAssociatedProduct $product
     */
    public function __invoke(Language $language, AbstractProduct $product, AbstractAttribute $binding): Response
    {
        $data = new ProductBindFormModel($product);
        $data->bindId = $binding->getId()->getValue();
        $violations = $this->validator->validate($data);

        if ($violations->count() === 0) {
            $this->commandBus->dispatch(new RemoveProductBindingCommand($product, $binding));

            return new EmptyResponse();
        }
        throw new ViolationsHttpException($violations);
    }
}
