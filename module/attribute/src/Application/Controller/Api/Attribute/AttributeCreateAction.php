<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Attribute\Application\Provider\AttributeFormProvider;
use Ergonode\Attribute\Infrastructure\Provider\CreateAttributeCommandFactoryProvider;

/**
 * @Route("/attributes", methods={"POST"})
 */
class AttributeCreateAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var AttributeFormProvider
     */
    private AttributeFormProvider $formProvider;

    /**
     * @var CreateAttributeCommandFactoryProvider
     */
    private CreateAttributeCommandFactoryProvider $factoryProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface                  $formFactory
     * @param AttributeFormProvider                 $formProvider
     * @param CreateAttributeCommandFactoryProvider $factoryProvider
     * @param CommandBusInterface                   $commandBus
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AttributeFormProvider $formProvider,
        CreateAttributeCommandFactoryProvider $factoryProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->factoryProvider = $factoryProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("ATTRIBUTE_CREATE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en"
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
     *     default="en",
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
        $type = $request->request->get('type');
        $request->request->remove('type');
        $class = $this->formProvider->provide($type);
        try {
            $form = $this->formFactory->create($class, null, ['validation_groups' => ['Default', 'Create']]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->factoryProvider->provide($type)->create($form);
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
