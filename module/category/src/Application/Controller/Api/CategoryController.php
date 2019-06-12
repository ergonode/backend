<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Category\Application\Form\CategoryCreateForm;
use Ergonode\Category\Application\Form\CategoryUpdateForm;
use Ergonode\Category\Application\Model\CategoryCreateFormModel;
use Ergonode\Category\Application\Model\CategoryUpdateFormModel;
use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Grid\CategoryGrid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class CategoryController extends AbstractApiController
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
     * @var CategoryRepositoryInterface
     */
    private $repository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param CategoryGrid                $categoryGrid
     * @param CategoryQueryInterface      $categoryQuery
     * @param CategoryRepositoryInterface $repository
     * @param MessageBusInterface         $messageBus
     */
    public function __construct(CategoryGrid $categoryGrid, CategoryQueryInterface $categoryQuery, CategoryRepositoryInterface $repository, MessageBusInterface $messageBus)
    {
        $this->categoryGrid = $categoryGrid;
        $this->categoryQuery = $categoryQuery;
        $this->repository = $repository;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/categories", methods={"GET"})
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
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getCategories(Language $language, Request $request): Response
    {
        $pagination = new RequestGridConfiguration($request);

        $dataSet = $this->categoryQuery->getDataSet($language);
        $grid = $this->categoryGrid->render($dataSet, $pagination, $language);

        return $this->createRestResponse($grid);
    }

    /**
     * @Route("/categories/{category}", methods={"GET"})
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
     *
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
     * @param string $category
     *
     * @return Response
     */
    public function getCategory(string $category): Response
    {
        $category = $this->repository->load(new CategoryId($category));

        if ($category) {
            return $this->createRestResponse($category);
        }

        return $this->createRestResponse(null, [], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/categories", methods={"POST"})
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
     *     description="Form validation error",
     * )
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     *
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

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            return $this->createRestResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid JSON format'], [], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([\get_class($exception), $exception->getMessage(), $exception->getTraceAsString()], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/categories/{category}", methods={"PUT"})
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
     *     @SWG\Schema(ref="#/definitions/category")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Update category",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param string  $category
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function updateCategory(string $category, Request $request): Response
    {
        try {
            $model = new CategoryUpdateFormModel();
            $form = $this->createForm(CategoryUpdateForm::class, $model, ['method' => 'PUT']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CategoryUpdateFormModel $data */
                $data = $form->getData();
                $command = new UpdateCategoryCommand(new CategoryId($category), new TranslatableString($data->name));
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            return $this->createRestResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid JSON format'], [], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([\get_class($exception), $exception->getMessage(), $exception->getTraceAsString()], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
    }
}
