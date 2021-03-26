<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Controller\Api\Bindings;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Product\Domain\Command\Bindings\AddProductBindingCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\Product\Application\Form\Product\Binding\ProductBindForm;
use Ergonode\Api\Application\Response\CreatedResponse;

/**
 * @Route(
 *     name="ergonode_product_bind_add",
 *     path="products/{product}/binding",
 *     methods={"POST"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AddProductAttributeBindingsAction extends AbstractController
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_POST_BINDING")
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
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add multiselect attribute binding",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_binding")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     */
    public function __invoke(Language $language, AbstractProduct $product, Request $request): Response
    {
        try {
            $model = new ProductBindFormModel($product);
            $form = $this->formFactory->create(ProductBindForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductBindFormModel $data */
                $data = $form->getData();
                $command = new AddProductBindingCommand(
                    $product,
                    new AttributeId($data->bindId),
                );
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
