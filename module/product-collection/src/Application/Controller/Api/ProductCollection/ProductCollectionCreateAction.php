<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Controller\Api\ProductCollection;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Application\Form\ProductCollectionCreateForm;
use Ergonode\ProductCollection\Application\Model\ProductCollectionCreateFormModel;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionCommand;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_product_collection_create",
 *     path="/collections",
 *     methods={"POST"}
 * )
 */
class ProductCollectionCreateAction
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param MessageBusInterface  $messageBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory
    ) {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @SWG\Tag(name="Product Collection")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
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
     * @param Request $request
     *
     * @return Response
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
                    $data->code,
                    new TranslatableString($data->name),
                    $data->typeId
                );
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
