<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api\Tree;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Category\Application\Form\Tree\CategoryTreeUpdateForm;
use Ergonode\Category\Application\Model\Tree\CategoryTreeUpdateFormModel;
use Ergonode\Category\Domain\Command\Tree\UpdateTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_category_tree_change",
 *     path="/trees/{tree}",
 *     methods={"PUT"},
 *     requirements={"tree"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CategoryTreeChangeAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("CATEGORY_PUT_TREE")
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
     *     default="en_GB",
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
     */
    public function __invoke(CategoryTree $tree, Request $request): Response
    {
        try {
            $model = new CategoryTreeUpdateFormModel();
            $form = $this
                ->formFactory
                ->create(CategoryTreeUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CategoryTreeUpdateFormModel $data */
                $data = $form->getData();
                $command =
                    new UpdateTreeCommand($tree->getId(), new TranslatableString($data->name), $data->categories);
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
