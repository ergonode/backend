<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api\Unit;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Application\Form\UnitForm;
use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Command\UpdateUnitCommand;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_unit_change",
 *     path="/units/{unit}",
 *     methods={"PUT"},
 *     requirements={"unit"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class UnitChangeAction
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
    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("SETTINGS_UPDATE")
     *
     * @SWG\Tag(name="Unit")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     *
     * @SWG\Parameter(
     *     name="unit",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Unit ID",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Unit body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/unit")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update unit",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Core\Domain\Entity\Unit")
     *
     * @param Unit    $unit
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Unit $unit, Request $request): Response
    {
        try {
            $model = new UnitFormModel();
            $form = $this->formFactory->create(
                UnitForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UnitFormModel $data */
                $data = $form->getData();
                $command = new UpdateUnitCommand(
                    $unit->getId(),
                    $data->name,
                    $data->symbol,
                );
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
