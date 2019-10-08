<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\CategoryTree\Application\Form\CategoryTreeCreateForm;
use Ergonode\CategoryTree\Application\Form\CategoryTreeUpdateForm;
use Ergonode\CategoryTree\Application\Model\CategoryTreeCreateFormModel;
use Ergonode\CategoryTree\Application\Model\CategoryTreeUpdateFormModel;
use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\CategoryTree\Domain\Command\DeleteTreeCommand;
use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Query\TreeQueryInterface;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\CategoryTree\Infrastructure\Grid\TreeGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class CategoryTreeController extends AbstractController
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
     * @IsGranted("CATEGORY_TREE_READ")
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
     *     description="Returns Category Tree",
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
        return new GridResponse($this->grid, $configuration, $this->query->getDataSet($language), $language);
    }

    /**
     * @Route("/trees/{tree}", methods={"GET"})
     *
     * @IsGranted("CATEGORY_TREE_READ")
     *
     * @SWG\Tag(name="Tree")
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
     *     description="Returns category tree",
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
        return new SuccessResponse($tree);
    }

    /**
     * @Route("/trees", methods={"POST"})
     *
     * @IsGranted("CATEGORY_TREE_CREATE")
     *
     * @SWG\Tag(name="Tree")
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
     *     description="Category tree body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/tree_req")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Create category tree",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     *
     * @todo Validation required
     */
    public function createTree(Request $request): Response
    {
        $model = new CategoryTreeCreateFormModel();
        $form = $this->createForm(CategoryTreeCreateForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $tree = $this->treeRepository->exists(CategoryTreeId::fromKey($data->code));

            if (!$tree) {
                $command = new CreateTreeCommand(new TranslatableString($data->name), $data->code);
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }

            throw new BadRequestHttpException('Tree already exists');
        }

        throw new FormValidationHttpException($form);
    }
    /**
     * @Route("/trees/{tree}", methods={"PUT"}, requirements={"tree"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CATEGORY_TREE_UPDATE")
     *
     * @SWG\Tag(name="Tree")
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
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\CategoryTree\Domain\Entity\CategoryTree")
     *
     * @param CategoryTree $tree
     * @param Request      $request
     *
     * @return Response
     */
    public function updateTree(CategoryTree $tree, Request $request): Response
    {
        try {
            $model = new CategoryTreeUpdateFormModel();
            $form = $this->createForm(CategoryTreeUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CategoryTreeUpdateFormModel $data */
                $data = $form->getData();
                $command = new UpdateTreeCommand($tree->getId(), new TranslatableString($data->name), $data->categories);
                $this->messageBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/trees/{tree}", methods={"DELETE"}, requirements={"tree"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CATEGORY_TREE_DELETE")
     *
     * @SWG\Tag(name="Tree")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="tree",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Tree ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\CategoryTree\Domain\Entity\CategoryTree")
     *
     * @param CategoryTree $tree
     *
     * @return Response
     */
    public function deleteTree(CategoryTree $tree): Response
    {
        $command = new DeleteTreeCommand($tree->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
