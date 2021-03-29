<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Condition\Infrastructure\Builder\ConditionSetValidatorBuilder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;

/**
 * @Route("/conditionsets", methods={"POST"})
 */
class ConditionSetCreateAction
{
    private ValidatorInterface $validator;

    private CommandBusInterface $commandBus;

    private NormalizerInterface $normalizer;

    private ConditionSetValidatorBuilder $conditionSetValidatorBuilder;

    public function __construct(
        ValidatorInterface $validator,
        CommandBusInterface $commandBus,
        NormalizerInterface $normalizer,
        ConditionSetValidatorBuilder $conditionSetValidatorBuilder
    ) {
        $this->validator = $validator;
        $this->commandBus = $commandBus;
        $this->normalizer = $normalizer;
        $this->conditionSetValidatorBuilder = $conditionSetValidatorBuilder;
    }

    /**
     * @SWG\Tag(name="Condition")
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
     *     description="Create condition set",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/conditionset")
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
     *
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

            $command = $this->normalizer->denormalize($data, CreateConditionSetCommand::class);
            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }
}
