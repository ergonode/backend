<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Condition\Infrastructure\Builder\CreateConditionSetValidatorBuilder;
use Ergonode\Condition\Infrastructure\Builder\UpdateConditionSetValidatorBuilder;
use Ergonode\Condition\Infrastructure\Grid\ConditionSetGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 */
class ConditionSetController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ConditionSetGrid
     */
    private $conditionSetGrid;

    /**
     * @var ConditionSetQueryInterface
     */
    private $conditionSetQuery;

    /**
     * @var CreateConditionSetValidatorBuilder
     */
    private $createConditionSetValidatorBuilder;

    /**
     * @var UpdateConditionSetValidatorBuilder
     */
    private $updateConditionSetValidatorBuilder;

    /**
     * @var SegmentQueryInterface
     */
    private $segmentQuery;

    /**
     * @param ValidatorInterface                 $validator
     * @param MessageBusInterface                $messageBus
     * @param SerializerInterface                $serializer
     * @param CreateConditionSetValidatorBuilder $createConditionSetValidatorBuilder
     * @param UpdateConditionSetValidatorBuilder $updateConditionSetValidatorBuilder
     * @param ConditionSetGrid                   $conditionSetGrid
     * @param ConditionSetQueryInterface         $conditionSetQuery
     * @param SegmentQueryInterface              $segmentQuery
     */
    public function __construct(
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        CreateConditionSetValidatorBuilder $createConditionSetValidatorBuilder,
        UpdateConditionSetValidatorBuilder $updateConditionSetValidatorBuilder,
        ConditionSetGrid $conditionSetGrid,
        ConditionSetQueryInterface $conditionSetQuery,
        SegmentQueryInterface $segmentQuery
    ) {
        $this->validator = $validator;
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
        $this->conditionSetGrid = $conditionSetGrid;
        $this->conditionSetQuery = $conditionSetQuery;
        $this->createConditionSetValidatorBuilder = $createConditionSetValidatorBuilder;
        $this->updateConditionSetValidatorBuilder = $updateConditionSetValidatorBuilder;
        $this->segmentQuery = $segmentQuery;
    }

    /**
     * @Route("/conditionsets", methods={"GET"})
     *
     * @IsGranted("CONDITION_READ")
     *
     * @SWG\Tag(name="Condition")
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
     *     enum={"id", "code", "name", "description"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC", "DESC"},
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
     *     enum={"COLUMN", "DATA"},
     *     description="Specify what response should containts"
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
     *     response=200,
     *     description="Returns condition set collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getConditionSets(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse(
            $this->conditionSetGrid,
            $configuration,
            $this->conditionSetQuery->getDataSet($language),
            $language
        );
    }

    /**
     * @Route("/conditionsets/{conditionSet}", methods={"GET"}, requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CONDITION_READ")
     *
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="conditionSet",
     *     in="path",
     *     type="string",
     *     description="Conditionset ID"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns conditionset"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\Condition\Domain\Entity\ConditionSet")
     *
     * @param ConditionSet $conditionSet
     *
     * @return Response
     */
    public function getConditionSet(ConditionSet $conditionSet): Response
    {
        return new SuccessResponse($conditionSet);
    }

    /**
     * @Route("/conditionsets", methods={"POST"})
     *
     * @IsGranted("CONDITION_CREATE")
     *
     * @SWG\Tag(name="Condition")
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
     *     description="Create condition set",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/conditionset_create")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns condition ID"
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
     */
    public function createConditionSet(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->createConditionSetValidatorBuilder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = ConditionSetId::fromCode(new ConditionSetCode($data['code']))->getValue();
            $data['name'] = $data['name'] ?? [];
            $data['description'] = $data['description'] ?? [];

            /** @var CreateConditionSetCommand $command */
            $command = $this->serializer->fromArray($data, CreateConditionSetCommand::class);
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }

    /**
     * @Route("/conditionsets/{conditionSet}", methods={"PUT"}, requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CONDITION_UPDATE")
     *
     * @SWG\Tag(name="Condition")
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
     *     description="Update condition set",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/conditionset_update")
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
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\Condition\Domain\Entity\ConditionSet")
     *
     * @param ConditionSet $conditionSet
     * @param Request      $request
     *
     * @return Response
     */
    public function updateConditionSet(ConditionSet $conditionSet, Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->updateConditionSetValidatorBuilder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = $conditionSet->getId()->getValue();

            /** @var UpdateConditionSetCommand $command */
            $command = $this->serializer->fromArray($data, UpdateConditionSetCommand::class);
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }

    /**
     * @Route("/conditionsets/{conditionSet}", methods={"DELETE"}, requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("CONDITION_DELETE")
     *
     * @SWG\Tag(name="Condition")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="conditionSet",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Condition set ID",
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
     *     response=409,
     *     description="Existing relations"
     * )
     *
     * @ParamConverter(class="Ergonode\Condition\Domain\Entity\ConditionSet")
     *
     * @param ConditionSet $conditionSet
     *
     * @return Response
     */
    public function deleteConditionSet(ConditionSet $conditionSet): Response
    {
        $segments = $this->segmentQuery->findIdByConditionSetId($conditionSet->getId());
        if (0 !== count($segments)) {
            throw new ConflictHttpException('Cannot delete condition set. Segments are assigned to it');
        }

        $command = new DeleteConditionSetCommand($conditionSet->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
