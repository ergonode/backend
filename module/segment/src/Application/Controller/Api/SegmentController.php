<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipTypeMessageBuilder;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Segment\Application\Form\CreateSegmentForm;
use Ergonode\Segment\Application\Form\Model\CreateSegmentFormModel;
use Ergonode\Segment\Application\Form\Model\UpdateSegmentFormModel;
use Ergonode\Segment\Application\Form\UpdateSegmentForm;
use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Infrastructure\Grid\SegmentGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class SegmentController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var SegmentGrid
     */
    private $grid;

    /**
     * @var SegmentQueryInterface
     */
    private $query;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @var ExistingRelationshipTypeMessageBuilder
     */
    private $existingRelationshipTypeMessageBuilder;

    /**
     * @param SegmentGrid                            $grid
     * @param SegmentQueryInterface                  $query
     * @param MessageBusInterface                    $messageBus
     * @param RelationshipsResolverInterface         $relationshipsResolver
     * @param ExistingRelationshipTypeMessageBuilder $existingRelationshipTypeMessageBuilder
     */
    public function __construct(
        SegmentGrid $grid,
        SegmentQueryInterface $query,
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipTypeMessageBuilder $existingRelationshipTypeMessageBuilder
    ) {
        $this->grid = $grid;
        $this->query = $query;
        $this->messageBus = $messageBus;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipTypeMessageBuilder = $existingRelationshipTypeMessageBuilder;
    }

    /**
     * @Route("segments", methods={"GET"})
     *
     * @IsGranted("SEGMENT_READ")
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
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
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns imported data collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getSegments(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse($this->grid, $configuration, $this->query->getDataSet($language), $language);
    }

    /**
     * @Route("/segments/{segment}", methods={"GET"}, requirements={"segment" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("SEGMENT_READ")
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="segment",
     *     in="path",
     *     type="string",
     *     description="Segment ID",
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
     * @ParamConverter(class="Ergonode\Segment\Domain\Entity\Segment")
     *
     * @param Segment $segment
     *
     * @return Response
     */
    public function getSegment(Segment $segment): Response
    {
        return new SuccessResponse($segment);
    }

    /**
     * @Route("/segments", methods={"POST"})
     *
     * @IsGranted("SEGMENT_CREATE")
     *
     * @SWG\Tag(name="Segment")
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
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createSegment(Request $request): Response
    {
        $form = $this->createForm(CreateSegmentForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateSegmentFormModel $data */
            $data = $form->getData();

            $command = new CreateSegmentCommand(
                new SegmentCode($data->code),
                new ConditionSetId($data->conditionSetId),
                new TranslatableString($data->name),
                new TranslatableString($data->description)
            );
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/segments/{segment}", methods={"PUT"}, requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("SEGMENT_UPDATE")
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
     *     default="EN"
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
     * @ParamConverter(class="Ergonode\Segment\Domain\Entity\Segment")
     *
     * @param Segment $segment
     * @param Request $request
     *
     * @return Response
     */
    public function updateSegment(Segment $segment, Request $request): Response
    {
        $model = new UpdateSegmentFormModel();
        $form = $this->createForm(UpdateSegmentForm::class, $model, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateSegmentFormModel $data */
            $data = $form->getData();

            $command = new UpdateSegmentCommand(
                $segment->getId(),
                new TranslatableString($data->name),
                new TranslatableString($data->description),
                new ConditionSetId($data->conditionSetId)
            );
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/segments/{segment}", methods={"DELETE"}, requirements={"segment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CONDITION_DELETE")
     *
     * @SWG\Tag(name="Segment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="segment",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Segment ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Segment\Domain\Entity\Segment")
     *
     * @param Segment $segment
     *
     * @return Response
     */
    public function deleteSegment(Segment $segment): Response
    {
        $relationships = $this->relationshipsResolver->resolve($segment->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipTypeMessageBuilder->build($relationships));
        }

        $command = new DeleteSegmentCommand($segment->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
