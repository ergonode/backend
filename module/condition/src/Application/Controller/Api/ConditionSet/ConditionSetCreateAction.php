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
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Condition\Infrastructure\Builder\CreateConditionSetValidatorBuilder;
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
     * @var CreateConditionSetValidatorBuilder
     */
    private $createConditionSetValidatorBuilder;

    /**
     * @param ValidatorInterface                 $validator
     * @param MessageBusInterface                $messageBus
     * @param SerializerInterface                $serializer
     * @param CreateConditionSetValidatorBuilder $createConditionSetValidatorBuilder
     */
    public function __construct(
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        CreateConditionSetValidatorBuilder $createConditionSetValidatorBuilder
    ) {
        $this->validator = $validator;
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
        $this->createConditionSetValidatorBuilder = $createConditionSetValidatorBuilder;
    }

    /**
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
    public function __invoke(Request $request): Response
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
}
