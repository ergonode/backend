<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Controller\Api\Tree;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Category\Application\Form\Tree\CategoryTreeCreateForm;
use Ergonode\Category\Application\Model\Tree\CategoryTreeCreateFormModel;
use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route("/trees", methods={"POST"})
 */
class CategoryTreeCreateAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_CATEGORY_POST_TREE")
     *
     * @SWG\Tag(name="Tree")
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
     *     description="Category tree body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/tree_request")
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
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): CategoryTreeId
    {
        $model = new CategoryTreeCreateFormModel();
        $form = $this->formFactory->create(CategoryTreeCreateForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $command = new CreateTreeCommand($data->code, new TranslatableString($data->name));
            $this->commandBus->dispatch($command);

            return $command->getId();
        }

        throw new FormValidationHttpException($form);
    }
}
