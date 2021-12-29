<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Option;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Attribute\Application\Form\Model\Option\SimpleOptionModel;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Application\Form\Model\Option\OptionMoveModel;
use Ergonode\Attribute\Application\Form\OptionMoveForm;
use Ergonode\Attribute\Domain\Command\Option\MoveOptionCommand;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;

/**
 * @Route(
 *     name="ergonode_option_move",
 *     path="/attributes/{attribute}/options/{option}/move",
 *     methods={"PUT"},
 *     requirements={
 *        "attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "option" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class OptionMoveAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_ATTRIBUTE_PUT_OPTION")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="option",
     *     in="path",
     *     type="string",
     *     description="Option id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Move attribute option",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/option_move")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns option id",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractOptionAttribute $attribute, AbstractOption $option, Request $request): AggregateId
    {
        try {
            if (!$attribute->hasOption($option->getId())) {
                throw new NotFoundHttpException();
            }

            $model = new OptionMoveModel($attribute->getId(), $option->getId());
            $form = $this->formFactory->create(OptionMoveForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var SimpleOptionModel $data */
                $data = $form->getData();

                $command = new MoveOptionCommand(
                    $option->getId(),
                    $attribute->getId(),
                    $data->after,
                    $data->positionId ? new AggregateId($data->positionId) : null,
                );

                $this->commandBus->dispatch($command);

                return $command->getId();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
