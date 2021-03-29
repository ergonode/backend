<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Controller\Api\ConditionSet;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Infrastructure\Builder\ConditionSetValidatorBuilder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;

/**
 * @Route(
 *     name="ergonode_condition_conditionset_change",
 *     path="/conditionsets/{conditionSet}",
 *     methods={"PUT"},
 *     requirements={"conditionSet"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ConditionSetChangeAction
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
     *     name="conditionSet",
     *     in="path",
     *     type="string",
     *     description="Condition Set Id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Update condition set",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/conditionset")
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
     */
    public function __invoke(ConditionSet $conditionSet, Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->conditionSetValidatorBuilder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = $conditionSet->getId()->getValue();

            /** @var UpdateConditionSetCommand $command */
            $command = $this->normalizer->denormalize($data, UpdateConditionSetCommand::class);
            $this->commandBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
