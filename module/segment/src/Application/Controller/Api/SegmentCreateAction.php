<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Application\Form\CreateSegmentForm;
use Ergonode\Segment\Application\Form\Model\CreateSegmentFormModel;
use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route("/segments", methods={"POST"})
 */
class SegmentCreateAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("SEGMENT_POST")
     *
     * @SWG\Tag(name="Segment")
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
     *     description="Add segment",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/segment")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns segment",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(CreateSegmentForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateSegmentFormModel $data */
            $data = $form->getData();

            $command = new CreateSegmentCommand(
                new SegmentCode($data->code),
                new TranslatableString($data->name),
                new TranslatableString($data->description),
                $data->conditionSetId ? new ConditionSetId($data->conditionSetId) : null
            );
            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }
}
