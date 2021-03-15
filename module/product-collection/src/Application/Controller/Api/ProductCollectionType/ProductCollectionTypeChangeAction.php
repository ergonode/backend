<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ProductCollection\Application\Form\ProductCollectionTypeUpdateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionTypeUpdateFormModel;
use Ergonode\ProductCollection\Domain\Command\UpdateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_type_change",
 *     path="/collections/type/{productCollectionType}",
 *     methods={"PUT"},
 *     requirements={"productCollectionType"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ProductCollectionTypeChangeAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("PRODUCT_COLLECTION_PUT_TYPE")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     *
     * @SWG\Parameter(
     *     name="productCollectionType",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Product collection type ID",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Category body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_collection_type_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update product collection type",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function __invoke(ProductCollectionType $productCollectionType, Request $request): Response
    {
        try {
            $model = new ProductCollectionTypeUpdateFormModel();
            $form = $this->formFactory->create(
                ProductCollectionTypeUpdateForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionTypeUpdateFormModel $data */
                $data = $form->getData();
                $command = new UpdateProductCollectionTypeCommand(
                    $productCollectionType->getId(),
                    new TranslatableString($data->name),
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
