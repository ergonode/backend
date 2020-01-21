<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Channel\Application\Form\ChannelCreateForm;
use Ergonode\Channel\Application\Model\ChannelCreateFormModel;
use Ergonode\Channel\Domain\Command\CreateChannelCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/channels", methods={"POST"})
 */
class ChannelCreateAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface $formFactory
     * @param CommandBusInterface  $commandBus
     */
    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("CHANNEL_CREATE")
     *
     * @SWG\Tag(name="Channel")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
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
     *     response=201,
     *     description="Returns attribute ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
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
        try {
            $model = new ChannelCreateFormModel();
            $form = $this->formFactory->create(ChannelCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var ChannelCreateFormModel $data */
                $data = $form->getData();
                $command = new CreateChannelCommand(
                    new TranslatableString($data->name),
                    new SegmentId($data->segmentId)
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
