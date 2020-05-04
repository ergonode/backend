<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Controller\Api\Tree;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Category\Application\Form\Tree\CategoryTreeCreateForm;
use Ergonode\Category\Application\Model\Tree\CategoryTreeCreateFormModel;
use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route("/trees", methods={"POST"})
 */
class CategoryTreeCreateAction
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
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("CATEGORY_TREE_CREATE")
     *
     * @SWG\Tag(name="Tree")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
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
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $model = new CategoryTreeCreateFormModel();
        $form = $this->formFactory->create(CategoryTreeCreateForm::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $command = new CreateTreeCommand($data->code, new TranslatableString($data->name));
            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }
}
