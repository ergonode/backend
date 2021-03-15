<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Application\Form\Model\CreateSegmentFormModel;
use Ergonode\Segment\Application\Form\Model\UpdateSegmentFormModel;
use Ergonode\Segment\Application\Form\UpdateSegmentForm;
use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_segment_change",
 *     path="/segments/{segment}",
 *     methods={"PUT"},
 *     requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class SegmentChangeAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    public function __construct(FormFactoryInterface $formFactory, CommandBusInterface $commandBus)
    {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("SEGMENT_PUT")
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="segment",
     *     in="path",
     *     type="string",
     *     description="Segment id",
     * )
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
     */
    public function __invoke(Segment $segment, Request $request): Response
    {
        $model = new UpdateSegmentFormModel();
        $form = $this->formFactory->create(UpdateSegmentForm::class, $model, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateSegmentFormModel $data */
            $data = $form->getData();

            $command = new UpdateSegmentCommand(
                $segment->getId(),
                new TranslatableString($data->name),
                new TranslatableString($data->description),
                $data->conditionSetId ? new ConditionSetId($data->conditionSetId) : null
            );
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }
}
