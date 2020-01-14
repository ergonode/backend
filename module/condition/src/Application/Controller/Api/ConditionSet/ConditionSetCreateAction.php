<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Infrastructure\Builder\ConditionSetValidatorBuilder;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/conditionsets", methods={"POST"})
 */
class ConditionSetCreateAction
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
     * @var ConditionSetValidatorBuilder
     */
    private $conditionSetValidatorBuilder;

    /**
     * @param ValidatorInterface           $validator
     * @param MessageBusInterface          $messageBus
     * @param SerializerInterface          $serializer
     * @param ConditionSetValidatorBuilder $conditionSetValidatorBuilder
     */
    public function __construct(
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        ConditionSetValidatorBuilder $conditionSetValidatorBuilder
    ) {
        $this->validator = $validator;
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
        $this->conditionSetValidatorBuilder = $conditionSetValidatorBuilder;
    }

    /**
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
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->conditionSetValidatorBuilder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = ConditionSetId::generate()->getValue();

            /** @var CreateConditionSetCommand $command */
            $command = $this->serializer->fromArray($data, CreateConditionSetCommand::class);
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }
}
