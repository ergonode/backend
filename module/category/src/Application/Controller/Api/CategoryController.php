<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Category\Application\Form\CategoryCreateForm;
use Ergonode\Category\Application\Form\CategoryUpdateForm;
use Ergonode\Category\Application\Model\CategoryCreateFormModel;
use Ergonode\Category\Application\Model\CategoryUpdateFormModel;
use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Infrastructure\Grid\CategoryGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Infrastructure\Resolver\RelationResolverInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class CategoryController extends AbstractController
{
    /**
     * @var CategoryGrid
     */
    private $categoryGrid;

    /**
     * @var CategoryQueryInterface
     */
    private $categoryQuery;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var RelationResolverInterface
     */
    private $relationResolver;

    /**
     * @param CategoryGrid              $categoryGrid
     * @param CategoryQueryInterface    $categoryQuery
     * @param MessageBusInterface       $messageBus
     * @param RelationResolverInterface $relationResolver
     */
    public function __construct(
        CategoryGrid $categoryGrid,
        CategoryQueryInterface $categoryQuery,
        MessageBusInterface $messageBus,
        RelationResolverInterface $relationResolver
    ) {
        $this->categoryGrid = $categoryGrid;
        $this->categoryQuery = $categoryQuery;
        $this->messageBus = $messageBus;
        $this->relationResolver = $relationResolver;
    }

    /**
     * @Route("/categories", methods={"GET"})
     *
     * @IsGranted("CATEGORY_READ")
     *
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"sku","name"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getCategories(Language $language, RequestGridConfiguration $configuration): Response
    {
        $dataSet = $this->categoryQuery->getDataSet($language);

        return new GridResponse($this->categoryGrid, $configuration, $dataSet, $language);
    }

    /**
     * @Route("/categories/{category}", methods={"GET"})
     *
     * @IsGranted("CATEGORY_READ")
     *
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="category",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Category ID",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Category\Domain\Entity\Category")
     *
     * @param Category $category
     *
     * @return Response
     */
    public function getCategory(Category $category): Response
    {
        return new SuccessResponse($category);
    }

    /**
     * @Route("/categories", methods={"POST"})
     *
     * @IsGranted("CATEGORY_CREATE")
     *
     * @SWG\Tag(name="Category")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
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
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createCategory(Request $request): Response
    {
        try {
            $model = new CategoryCreateFormModel();
            $form = $this->createForm(CategoryCreateForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CategoryCreateFormModel $data */
                $data = $form->getData();
                $command = new CreateCategoryCommand($data->code, new TranslatableString($data->name));
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/categories/{category}", methods={"PUT"})
     *
     * @IsGranted("CATEGORY_UPDATE")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
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
     *     @SWG\Schema(ref="#/definitions/category_upd")
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
     * @ParamConverter(class="Ergonode\Category\Domain\Entity\Category")
     *
     * @param Category $category
     * @param Request  $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateCategory(Category $category, Request $request): Response
    {
        try {
            $model = new CategoryUpdateFormModel();
            $form = $this->createForm(CategoryUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CategoryUpdateFormModel $data */
                $data = $form->getData();
                $command = new UpdateCategoryCommand($category->getId(), new TranslatableString($data->name));
                $this->messageBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/categories/{category}", methods={"DELETE"}, requirements={"category"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CATEGORY_DELETE")
     *
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="category",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Category ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Can't delete category",
     * )
     *
     * @ParamConverter(class="Ergonode\Category\Domain\Entity\Category")
     *
     * @param Category $category
     *
     * @return Response
     */
    public function deleteCategory(Category $category): Response
    {
        if ($this->relationResolver->resolve($category->getId())) {
            throw new ConflictHttpException('The category cannot be removed because it has active relationships');
        }

        $command = new DeleteCategoryCommand($category->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
