<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Controller\Api;

use Ergonode\CategoryTree\Application\Form\TreeForm;
use Ergonode\CategoryTree\Application\Model\TreeFormModel;
use Ergonode\CategoryTree\Domain\Command\AddCategoryCommand;
use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Query\TreeQueryInterface;
use Ergonode\CategoryTree\Infrastructure\Grid\TreeGrid;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class CategoryTreeController extends AbstractApiController
{
    /**
     * @var TreeRepositoryInterface
     */
    private $treeRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var TreeQueryInterface
     */
    private $query;

    /**
     * @var TreeGrid
     */
    private $grid;

    /**
     * @param TreeRepositoryInterface $treeRepository
     * @param MessageBusInterface     $messageBus
     * @param TreeQueryInterface      $query
     * @param TreeGrid                $grid
     */
    public function __construct(
        TreeRepositoryInterface $treeRepository,
        MessageBusInterface $messageBus,
        TreeQueryInterface $query,
        TreeGrid $grid
    ) {
        $this->treeRepository = $treeRepository;
        $this->messageBus = $messageBus;
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @Route("/trees", methods={"GET"})
     *
     * @SWG\Tag(name="Tree")
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
     *     description="Returns Category  Tree",
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
        $configuration = new RequestGridConfiguration($request);

        $dataSet = $this->query->getDataSet($language);
        $result = $this->renderGrid($this->grid, $configuration, $dataSet, $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/trees", methods={"POST"})
     *
     * @SWG\Tag(name="Tree")
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
     *     name="name",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="Tree name",
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="Tree code",
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
     */
    public function createTree(Request $request): Response
    {
        $name = $request->request->get('name');
        $code = $request->request->get('code');

        if ($name && $code) {
            $tree = $this->treeRepository->exists(CategoryTreeId::fromKey($code));
            if (!$tree) {
                $command = new CreateTreeCommand($name, $code);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()->getValue()], [], Response::HTTP_CREATED);
            }

            return $this->createRestResponse(['message' => 'tree already exists'], [], Response::HTTP_BAD_REQUEST);
        }

        return $this->createRestResponse([], [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/trees/{tree}/category/{category}/child", methods={"POST"})
     *
     * @SWG\Tag(name="Tree")
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
     *     name="tree",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Id of category tree",
     * )
     * @SWG\Parameter(
     *     name="category",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Id of category in tree",
     * )
     * @SWG\Parameter(
     *     name="child",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="Id of added child category",
     * )
     * @SWG\Response(
     *     response=202,
     *     description="Action accepted",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param string  $tree
     * @param string  $category
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function addCategory(string $tree, string $category, Request $request): Response
    {
        $child = $request->request->get('child');

        if ($child) {
            $command = new AddCategoryCommand(new CategoryTreeId($tree), new CategoryId($category), new CategoryId($child));
            $this->messageBus->dispatch($command);

            return $this->createRestResponse([], [], Response::HTTP_ACCEPTED);
        }

        return $this->createRestResponse([], [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/trees/{tree}", methods={"PUT"})
     *
     * @SWG\Tag(name="Tree")
     *
     * @SWG\Parameter(
     *     name="tree",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Id of category tree",
     * )
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
     *     description="Update category tree",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/tree")
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
     * @param string  $tree
     * @param Request $request
     *
     * @return Response
     */
    public function putTree(string $tree, Request $request): Response
    {
        try {
            $model = new TreeFormModel();
            $form = $this->createForm(TreeForm::class, $model, ['method' => 'PUT']);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TreeFormModel $data */
                $data = $form->getData();

                $command = new UpdateTreeCommand(new CategoryTreeId($tree), $data->name, $data->categories);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse($data, [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            return $this->createRestResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid JSON format'], [], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([\get_class($exception), $exception->getMessage(), $exception->getTraceAsString()], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form, [$form->getErrors()->count()], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/trees/{tree}", methods={"GET"})
     *
     * @SWG\Tag(name="Tree")
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
     *     name="tree",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="tree ID",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Language",
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
     * @ParamConverter(class="Ergonode\CategoryTree\Domain\Entity\CategoryTree")
     *
     * @param CategoryTree $tree
     * @param Language     $language
     *
     * @return Response
     */
    public function getTree(CategoryTree $tree, Language $language): Response
    {
        return $this->createRestResponse($tree);
    }
}
