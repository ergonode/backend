<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollectionType;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ProductCollection\Application\Form\ProductCollectionTypeCreateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionTypeCreateFormModel;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionTypeCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;

/**
 * @Route(
 *     name="ergonode_product_collection_type_create",
 *     path="/collections/type",
 *     methods={"POST"}
 * )
 */
class ProductCollectionTypeCreateAction
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
     * @IsGranted("PRODUCT_COLLECTION_POST_TYPE")
     *
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add product collection type",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_collection_type_create")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns product collection type ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        try {
            $model = new ProductCollectionTypeCreateFormModel();
            $form = $this->formFactory->create(ProductCollectionTypeCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionTypeCreateFormModel $data */
                $data = $form->getData();
                $command = new CreateProductCollectionTypeCommand(
                    new ProductCollectionTypeCode($data->code),
                    new TranslatableString($data->name),
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
