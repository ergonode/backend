<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Controller\Api;

use Ergonode\CategoryTree\Domain\Command\AddCategoryCommand;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Query\TreeQueryInterface;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 */
class CategoryTreeController extends AbstractApiController
{
    /**
     * @var TreeQueryInterface
     */
    private $query;

    /**
     * @var TreeRepositoryInterface
     */
    private $treeRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param TreeQueryInterface      $query
     * @param TreeRepositoryInterface $treeRepository
     * @param MessageBusInterface     $messageBus
     */
    public function __construct(TreeQueryInterface $query, TreeRepositoryInterface $treeRepository, MessageBusInterface $messageBus)
    {
        $this->query = $query;
        $this->treeRepository = $treeRepository;
        $this->messageBus = $messageBus;
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
     * @todo change transactions in future version
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
     * @Route("/trees/{tree}", methods={"GET"})
     * @Route("/trees/{tree}/{category}", methods={"GET"})
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
     * @SWG\Parameter(
     *     name="category",
     *     in="path",
     *     type="string",
     *     description="Parent Category",
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
     * @param string   $tree
     * @param Language $language
     * @param string   $category
     *
     * @return Response
     */
    public function getTree(string $tree, Language $language, ?string $category = null): Response
    {
        $categoryId = $category ? new CategoryId($category) : null;

        $result = $this->query->getCategory(new CategoryTreeId($tree), $language, $categoryId);

        return $this->createRestResponse($result);
    }
}
