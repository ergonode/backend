<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Controller\Api;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\Provider\ConditionConfigurationProvider;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Condition\Infrastructure\Builder\ConditionSetValidatorBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 */
class ConditionController extends AbstractController
{
    /**
     * @var ConditionConfigurationProvider
     */
    private $provider;

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
    private $builder;

    /**
     * @param ConditionConfigurationProvider $provider
     * @param ValidatorInterface             $validator
     * @param MessageBusInterface            $messageBus
     * @param SerializerInterface            $serializer
     * @param ConditionSetValidatorBuilder   $builder
     */
    public function __construct(
        ConditionConfigurationProvider $provider,
        ValidatorInterface $validator,
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        ConditionSetValidatorBuilder $builder
    ) {
        $this->provider = $provider;
        $this->validator = $validator;
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
        $this->builder = $builder;
    }

    /**
     * @Route("/conditions/{condition}", methods={"GET"})
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
     *     name="condition",
     *     in="path",
     *     type="string",
     *     description="Condition ID"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns condition"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @param Language $language
     * @param string   $condition
     *
     * @return Response
     */
    public function getCondition(Language $language, string $condition): Response
    {
        $configuration = $this->provider->getConfiguration($language, $condition);

        return new SuccessResponse($configuration);
    }

    /**
     * @Route("/conditions", methods={"POST"})
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
     *     @SWG\Schema(ref="#/definitions/condition")
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
    public function createCondition(Request $request): Response
    {
        $data = $request->request->all();

        $violations = $this->validator->validate($data, $this->builder->build($data));
        if (0 === $violations->count()) {
            $data['id'] = ConditionSetId::fromCode(new ConditionSetCode($data['code']))->getValue();

            /** @var CreateConditionSetCommand $command */
            $command = $this->serializer->fromArray($data, CreateConditionSetCommand::class);
            $this->messageBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new ViolationsHttpException($violations);
    }
}
