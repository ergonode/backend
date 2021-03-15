<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ProductCollection\Application\Form\ProductCollectionCreateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionCreateFormModel;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;

/**
 * @Route(
 *     name="ergonode_product_collection_create",
 *     path="/collections",
 *     methods={"POST"}
 * )
 */
class ProductCollectionCreateAction
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
     * @IsGranted("PRODUCT_COLLECTION_POST")
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
     *     description="Add product collection",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/product_collection_create")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns product collection ID",
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
            $model = new ProductCollectionCreateFormModel();
            $form = $this->formFactory->create(ProductCollectionCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ProductCollectionCreateFormModel $data */
                $data = $form->getData();
                $command = new CreateProductCollectionCommand(
                    new ProductCollectionCode($data->code),
                    new TranslatableString($data->name),
                    new TranslatableString($data->description),
                    new ProductCollectionTypeId($data->typeId)
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
