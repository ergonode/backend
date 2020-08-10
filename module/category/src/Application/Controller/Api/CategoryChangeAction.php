<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Category\Application\Provider\CategoryFormProvider;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Infrastructure\Provider\UpdateCategoryCommandFactoryProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_category_change",
 *     path="/categories/{category}",
 *     methods={"PUT"},
 *     requirements={"category"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CategoryChangeAction
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
     * @var CategoryFormProvider
     */
    private CategoryFormProvider $formProvider;

    /**
     * @var UpdateCategoryCommandFactoryProvider
     */
    private UpdateCategoryCommandFactoryProvider $factoryProvider;

    /**
     * @param CommandBusInterface                  $commandBus
     * @param FormFactoryInterface                 $formFactory
     * @param CategoryFormProvider                 $formProvider
     * @param UpdateCategoryCommandFactoryProvider $factoryProvider
     */
    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        CategoryFormProvider $formProvider,
        UpdateCategoryCommandFactoryProvider $factoryProvider
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->factoryProvider = $factoryProvider;
    }


    /**
     * @IsGranted("CATEGORY_UPDATE")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     *
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="category",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Category ID",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Category body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/category_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update category",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Category\Domain\Entity\AbstractCategory")
     *
     * @param AbstractCategory $category
     * @param Request          $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractCategory $category, Request $request): Response
    {
        $formClass = $this->formProvider->provide($category->getType());

        try {
            $form = $this->formFactory->create($formClass, null, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->factoryProvider->provide($category->getType())->create($category->getId(), $form);
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
