<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\Unit;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Core\Application\Form\UnitForm;
use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Command\CreateUnitCommand;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_unit_create",
 *     path="/units",
 *     methods={"POST"}
 * )
 */
class UnitCreateAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("CORE_POST_UNIT")
     *
     * @SWG\Tag(name="Unit")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add unit",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/unit")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns unit ID",
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
    public function __invoke(Request $request): Response
    {
        try {
            $model = new UnitFormModel();
            $form = $this->formFactory->create(UnitForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UnitFormModel $data */
                $data = $form->getData();
                $command = new CreateUnitCommand(
                    $data->name,
                    $data->symbol
                );
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
