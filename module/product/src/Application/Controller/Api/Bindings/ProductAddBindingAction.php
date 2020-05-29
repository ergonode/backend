<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Controller\Api\Bindings;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Product\Domain\Command\Bindings\AddProductBindingCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Application\Model\Product\Binding\ProductBindFormModel;
use Ergonode\Product\Application\Form\Product\Binding\ProductBindForm;

/**
 * @Route(
 *     name="ergonode_product_bind_add",
 *     path="products/{product}/binding",
 *     methods={"POST"},
 *     requirements={"product"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductAddBindingAction extends AbstractController
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
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
     *     required=true,
     *     default="en",
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
     *
     * @ParamConverter(class="Ergonode\Product\Domain\Entity\AbstractProduct")
     *
     *
     * @param Language        $language
     * @param AbstractProduct $product
     * @param Request         $request
     *
     * @return Response
     */
    public function __invoke(Language $language, AbstractProduct $product, Request $request): Response
    {
        try {
            $model = new ProductBindFormModel();
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

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
