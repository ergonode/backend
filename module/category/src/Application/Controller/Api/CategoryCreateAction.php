<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Category\Application\Provider\CategoryFormProvider;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Infrastructure\Provider\CreateCategoryCommandFactoryProvider;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route("/categories", methods={"POST"})
 */
class CategoryCreateAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    private CategoryFormProvider $formProvider;

    private CreateCategoryCommandFactoryProvider $factoryProvider;

    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        CategoryFormProvider $formProvider,
        CreateCategoryCommandFactoryProvider $factoryProvider
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->factoryProvider = $factoryProvider;
    }


    /**
     * @IsGranted("CATEGORY_POST")
     *
     * @SWG\Tag(name="Category")
     *
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
     *     description="Category body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/category")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Create category",
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
    public function __invoke(Request $request): CategoryId
    {
        $type = $request->request->get('type', Category::TYPE);
        $request->request->remove('type');
        $class = $this->formProvider->provide($type);
        try {
            $form = $this->formFactory->create($class, null, ['validation_groups' => ['Default', 'Create']]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->factoryProvider->provide($type)->create($form);
                $this->commandBus->dispatch($command);

                return $command->getId();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
