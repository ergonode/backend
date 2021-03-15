<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Attribute\Application\Provider\AttributeFormProvider;
use Ergonode\Attribute\Infrastructure\Provider\UpdateAttributeCommandFactoryProvider;

/**
 * @Route(
 *     name="ergonode_attribute_change",
 *     path="/attributes/{attribute}",
 *     methods={"PUT"},
 *     requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeChangeAction
{
    private CommandBusInterface $commandBus;

    private AttributeFormProvider $formProvider;

    private UpdateAttributeCommandFactoryProvider $factoryProvider;

    private FormFactoryInterface $formFactory;

    public function __construct(
        CommandBusInterface $commandBus,
        AttributeFormProvider $formProvider,
        UpdateAttributeCommandFactoryProvider $factoryProvider,
        FormFactoryInterface $formFactory
    ) {
        $this->commandBus = $commandBus;
        $this->formProvider = $formProvider;
        $this->factoryProvider = $factoryProvider;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ATTRIBUTE_PUT")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @throws \Exception
     */
    public function __invoke(AbstractAttribute $attribute, Request $request): Response
    {
        $formClass = $this->formProvider->provide($attribute->getType());

        try {
            $form = $this->formFactory->create($formClass, null, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->factoryProvider->provide($attribute->getType())->create($attribute->getId(), $form);
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
